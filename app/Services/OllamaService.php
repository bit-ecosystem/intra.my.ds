<?php

declare(strict_types=1);

namespace App\Services;

use Bites\Kbm\Dms\Models\Document;
use Bites\Kbm\Dms\Models\Vector;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportStreaming\HandlesStreaming;

class OllamaService
{
    use HandlesStreaming;

    protected string $baseUrl = 'http://localhost:11434/api';

    /**
     * Default options (can be overridden).
     */
    public static array $defaultOptions = [
        'temperature' => 0.7,
        'num_predict' => 256,
        'stream' => false,
    ];

    /**
     * Default format schema (can be overridden).
     */
    public static array $defaultFormat = [
        'type' => 'object',
        'properties' => [
            'countries' => [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'country' => ['type' => 'string'],
                        'population' => ['type' => 'integer'],
                    ],
                    'required' => ['country', 'population'],
                ],
            ],
        ],
        'required' => ['countries'],
    ];

    /**
     * List available models from Ollama.
     */
    public function listModels(): array
    {
        $res = Http::get($this->baseUrl.'/tags');

        return collect($res->json('models') ?? [])->pluck('name')->toArray();
    }

    /**
     * Stream chat response from Ollama.
     */
    public function streamChat(string $prompt, string $model, callable $onChunk): void
    {
        try {
            $response = Http::timeout(30)->withOptions(['stream' => true])
                ->post($this->baseUrl.'/generate', [
                    'model' => $model,
                    'prompt' => $prompt,
                    'stream' => true,
                ]);

            $body = $response->toPsrResponse()->getBody();

            while (! $body->eof()) {
                $chunk = $body->read(1024);
                foreach (explode("\n", trim($chunk)) as $line) {
                    if ($line !== '' && $line !== '0') {
                        $data = json_decode($line, true);
                        if (isset($data['response']) && $onChunk($data['response']) === false) {
                            break 2;
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            Log::error('Ollama streamChat failed: '.$exception->getMessage());
            $onChunk('[Ollama is unavailable. Please try again later.]');
        }
    }

    /**
     * Generate embedding for a given text using Ollama.
     */
    public function generateEmbedding(string $text, string $model = 'nomic-embed-text'): ?array
    {
        try {
            $response = Http::post($this->baseUrl.'/embeddings', [
                'model' => $model,
                'input' => $text,
            ]);

            return $response->json('embedding') ?? null;
        } catch (Exception $exception) {
            Log::error('Embedding generation failed: '.$exception->getMessage());

            return null;
        }
    }

    /**
     * Chunk document content into smaller pieces.
     */
    public function chunkText(string $text, int $chunkSize = 500): array
    {
        $words = preg_split('/\s+/', $text);

        return array_chunk($words, $chunkSize);
    }

    /**
     * Index a document: chunk, embed, and store vectors.
     */
    public function indexDocument(Document $document, string $model = 'nomic-embed-text'): void
    {
        if (! $document->content) {
            return;
        }

        $chunks = $this->chunkText($document->content);
        foreach ($chunks as $chunk) {
            $chunkText = implode(' ', $chunk);
            $embedding = $this->generateEmbedding($chunkText, $model);

            if ($embedding) {
                Vector::create([
                    'document_id' => $document->id,
                    'model' => $model,
                    'chunk_text' => $chunkText,
                    'vector' => json_encode($embedding),
                    'metadata' => json_encode(['classification' => $document->classification_level]),
                ]);
            }
        }
    }

    /**
     * Perform similarity search (basic cosine similarity in PHP).
     */
    public function searchSimilar(string $query, string $model = 'nomic-embed-text', int $limit = 5): array
    {
        $queryEmbedding = $this->generateEmbedding($query, $model);
        if (! $queryEmbedding) {
            return [];
        }

        $vectors = Vector::where('model', $model)->get();
        $results = [];

        foreach ($vectors as $vector) {
            $score = $this->cosineSimilarity($queryEmbedding, json_decode($vector->vector, true));
            $results[] = ['chunk' => $vector->chunk_text, 'score' => $score];
        }

        return collect($results)->sortByDesc('score')->take($limit)->toArray();
    }

    /**
     * Compute cosine similarity.
     */
    protected function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0;
        $normA = 0;
        $normB = 0;
        foreach ($a as $i => $val) {
            $dot += $val * $b[$i];
            $normA += $val ** 2;
            $normB += $b[$i] ** 2;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Generate answer with RAG context.
     */
    public function answerWithContext(string $query, string $model = 'llama3'): string
    {
        $contextChunks = $this->searchSimilar($query);
        $context = implode("\n", array_column($contextChunks, 'chunk'));

        $prompt = "Use the following context to answer:\n\n{$context}\n\nQuestion: {$query}";

        $response = Http::post($this->baseUrl.'/generate', [
            'model' => $model,
            'prompt' => $prompt,
        ]);

        return $response->json('response') ?? '[No response]';
    }

    public function generateStructured(string $prompt, string $schema, string $model = 'qwen2.5-coder:3b'): array
    {
        $response = Http::timeout(45)->post($this->baseUrl.'/generate', [
            'model' => $model,
            'prompt' => $prompt,
            'format' => json_decode($schema, true), // Ollama supports JSON schema
        ]);

        return $response->json('response') ?? [];
    }
}
