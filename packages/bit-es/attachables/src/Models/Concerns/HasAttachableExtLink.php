<?php

declare(strict_types=1);

namespace Bites\Attachables\Models\Concerns;

use Bites\Attachables\Models\AttachableExtLink;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasAttachableExtLink
{
    public function attachableLink(): MorphOne
    {
        return $this->morphOne(AttachableExtLink::class, 'attachable');
    }

    public function setLink(string $url): AttachableExtLink
    {
        return tap(
            $this->attachableLink()->firstOrNew(),
            fn ($link) => $link->fill(['url' => trim($url)])->save(),
        );
    }

    public function clearLink(): void
    {
        $this->attachableLink()->delete();
    }

    public function getLink(): ?string
    {
        return optional($this->attachableLink)->url;
    }

    public static function FormComponent(
        string $relationship = 'attachableLink',
        string $column = 'url',
        string $sectionLabel = 'External Link',
        string $sectionDescription = 'Add an external URL link (e.g. https://www.example.com) associated with this item.'
    ): Section {
        return Section::make($sectionLabel)
            ->description($sectionDescription)
            ->schema([
                Group::make()
                    ->relationship($relationship) // bind here
                    ->schema([
                        TextInput::make($column)
                            ->label('')
                            ->url()
                            ->maxLength(2048)
                            ->dehydrateStateUsing(fn ($state) => trim((string) $state)),
                    ]),
            ])
            ->collapsible();
    }
}
