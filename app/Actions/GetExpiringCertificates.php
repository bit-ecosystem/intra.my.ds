<?php

declare(strict_types=1);

namespace App\Actions;

use Bites\Business\Lms\Entities\Certificate;
use Illuminate\Database\Eloquent\Builder;

class GetExpiringCertificates
{
    public function execute(int $staffId): Builder
    {
        return Certificate::query()
            ->where('for_staff', $staffId)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('status', 'valid')
                        ->whereNotNull('expires_at')
                        ->where('expires_at', '<=', now()->addMonth());
                })
                    ->orWhere(function ($q) {
                        $q->where('status', 'expired')
                            ->where('expires_at', '>=', now()->subMonth());
                    });
            });
    }
}
