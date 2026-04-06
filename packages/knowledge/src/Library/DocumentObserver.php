<?php

declare(strict_types=1);

namespace Bites\Knowledge\Library;

use App\Jobs\VectorizeDocument;

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
