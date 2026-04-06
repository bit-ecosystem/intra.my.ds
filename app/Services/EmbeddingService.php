<?php

declare(strict_types=1);

namespace App\Services;

namespace App\Services;

use Bites\Knowledge\Library\Document;
use Bites\Knowledge\Library\Vector;
use Illuminate\Support\Facades\Http;

class EmbeddingService
{
    public function process(Document $document): void
    {
        $chunks = $this->chunkText($document->content);
        foreach ($chunks as $chunk) {
            $embedding = $this->getEmbedding($chunk);
            Vector::create([
                'document_id' => $document->id,
                'model' => 'text-embedding-3-large',
                'chunk_text' => $chunk,
                'vector' => $embedding,
            ]);
        }
    }

    protected function chunkText($text): array
    {
        return str_split($text, 1000); // simple chunking
    }

    public function getEmbedding(string $text): array
    {
        $response = Http::withToken(env('OPENAI_KEY'))
            ->post('https://api.openai.com/v1/embeddings', [
                'model' => 'text-embedding-3-large',
                'input' => $text,
            ])->json();

        return $response['data'][0]['embedding'];
    }
}
