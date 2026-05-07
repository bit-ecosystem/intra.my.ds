<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Attachments\Pages;

use App\Enums\DocType;
use Bites\Business\Lms\Http\UI\Admin\Resources\Attachments\AttachmentResource;
use Bites\Knowledge\Library\Attachment;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAttachments extends ListRecords
{
    protected static string $resource = AttachmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All'),
            // Optional: add a badge count
            // ->badge((string) Order::query()->count())
        ];

        // Iterate through all cases of the enum
        foreach (DocType::cases() as $type) {
            $tabs[$type->value] = Tab::make($type->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', $type->value))
                // Optional: add a badge count for each type
                ->badge((string) Attachment::query()->where('type', $type->value)->count());
        }

        return $tabs;
    }
}
