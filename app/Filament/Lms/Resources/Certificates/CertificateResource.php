<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Certificates;

use App\Filament\Lms\Resources\Certificates\Pages\CreateCertificate;
use App\Filament\Lms\Resources\Certificates\Pages\EditCertificate;
use App\Filament\Lms\Resources\Certificates\Pages\ListCertificates;
use App\Filament\Lms\Resources\Certificates\Pages\ViewCertificate;
use App\Filament\Lms\Resources\Certificates\Schemas\CertificateForm;
use App\Filament\Lms\Resources\Certificates\Schemas\CertificateInfolist;
use App\Filament\Lms\Resources\Certificates\Tables\CertificatesTable;
use BackedEnum;
use Bites\Kbm\Lms\Models\Certificate;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-certificate';

    protected static string|UnitEnum|null $navigationGroup = 'Report Card';

    protected static ?string $modelLabel = 'Certificates';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return CertificateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CertificateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CertificatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCertificates::route('/'),
            // 'create' => CreateCertificate::route('/create'),
            'view' => ViewCertificate::route('/{record}'),
            // 'edit' => EditCertificate::route('/{record}/edit'),
        ];
    }
}
