<?php

declare(strict_types=1);

namespace App\Services;

use Bites\Kbm\Dms\Models\Document;
use Bites\Kbm\Dms\Models\Vector;
use Illuminate\Support\Facades\Http;

class RagService
{
    // public function __construct(protected EmbeddingService $embeddingService) {}

    // public function query(string $question): array
    // {
    //     $queryEmbedding = $this->embeddingService->getEmbedding($question);

    //     // Similarity search (pgvector or manual cosine similarity)
    //     $vectors = Vector::all()->map(function($v) use ($queryEmbedding) {
    //         $v->score = $this->cosineSimilarity($queryEmbedding, $v->vector);
    //         return $v;
    //     })->sortByDesc('score')->take(3);

    //     $context = $vectors->pluck('chunk_text')->implode("\n");

    //     $answer = $this->askLLM($question, $context);

    //     return [$answer, $vectors->map(fn($v) => ['title' => $v->document->title])];
    // }

    // protected function askLLM($question, $context)
    // {
    //     $response = Http::withToken(env('OPENAI_KEY'))
    //         ->post('https://api.openai.com/v1/chat/completions', [
    //             'model' => 'gpt-4',
    //             'messages' => [
    //                 ['role' => 'system', 'content' => "Use the following context:\n$context"],
    //                 ['role' => 'user', 'content' => $question],
    //             ],
    //         ])->json();
    //     return $response['choices'][0]['message']['content'];
    // }

    // protected function cosineSimilarity($a, $b)
    // {
    //     $dot = array_sum(array_map(fn($x,$y)=>$x*$y,$a,$b));
    //     $normA = sqrt(array_sum(array_map(fn($x)=>$x*$x,$a)));
    //     $normB = sqrt(array_sum(array_map(fn($x)=>$x*$x,$b)));
    //     return $dot / ($normA * $normB);
    // }
}
