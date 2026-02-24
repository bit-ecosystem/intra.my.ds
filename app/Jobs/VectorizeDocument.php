<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Dms\Document;
use App\Models\Dms\Vector;
use App\Services\OllamaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VectorizeDocument implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected int $documentId) {}

    /**
     * Execute the job.
     */
    public function handle(OllamaService $ollamaService): void
    {
        $document = Document::find($this->documentId);

        if (! $document) {
            return;
        }

        $text = $document->title."\n\n".strip_tags((string) $document->content);

        $embedding = $ollamaService->embed($text);

        if (empty($embedding)) {
            return;
        }

        Vector::updateOrCreate(
            ['document_id' => $document->id, 'model' => config('dms.ollama_model')],
            ['vector' => $embedding]
        );
    }
}
