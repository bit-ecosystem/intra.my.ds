<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Attachments;

use App\Filament\Lms\Resources\Attachments\Pages\CreateAttachment;
use App\Filament\Lms\Resources\Attachments\Pages\EditAttachment;
use App\Filament\Lms\Resources\Attachments\Pages\ListAttachments;
use App\Filament\Lms\Resources\Attachments\Pages\ViewAttachment;
use App\Filament\Lms\Resources\Attachments\Schemas\AttachmentForm;
use App\Filament\Lms\Resources\Attachments\Schemas\AttachmentInfolist;
use App\Filament\Lms\Resources\Attachments\Tables\AttachmentsTable;
use BackedEnum;
use Bites\Knowledge\Library\Attachment;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AttachmentResource extends Resource
{
    protected static ?string $model = Attachment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Library';

    protected static ?string $modelLabel = 'Documents';

    public static function form(Schema $schema): Schema
    {
        return AttachmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AttachmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttachmentsTable::configure($table);
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
            'index' => ListAttachments::route('/'),
            'create' => CreateAttachment::route('/create'),
            'view' => ViewAttachment::route('/{record}'),
            'edit' => EditAttachment::route('/{record}/edit'),
        ];
    }
}
