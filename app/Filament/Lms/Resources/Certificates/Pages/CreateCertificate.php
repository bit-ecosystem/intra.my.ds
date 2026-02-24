<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Certificates\Pages;

use App\Filament\Lms\Resources\Certificates\CertificateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCertificate extends CreateRecord
{
    protected static string $resource = CertificateResource::class;
}
