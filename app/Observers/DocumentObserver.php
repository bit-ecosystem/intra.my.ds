<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\VectorizeDocument;
use Bites\Kbm\Dms\Models\Document;

class DocumentObserver
{
    public function created(Document $document): void
    {
        $this->maybeDispatchVectorJob($document);
    }

    public function updated(Document $document): void
    {
        $this->maybeDispatchVectorJob($document);
    }

    protected function maybeDispatchVectorJob(Document $document): void
    {
        // if ($document->classification_level <= 2) {
        //     if (config('dms.vectorize_sync')) {
        //         VectorizeDocument::dispatchSync($document->id);
        //     } else {
        //         VectorizeDocument::dispatch($document->id);
        //     }
        // }
    }
}
