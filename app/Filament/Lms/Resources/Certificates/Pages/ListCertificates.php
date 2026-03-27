<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Certificates\Pages;

use App\Filament\Lms\Resources\Certificates\CertificateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCertificates extends ListRecords
{
    protected static string $resource = CertificateResource::class;

    public function getSubheading(): ?string
    {
        return __('All certificates issued to staff, for quizzes and courses. View details, or recertify if applicable. Expiring certs will appear in To Do > Task');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
