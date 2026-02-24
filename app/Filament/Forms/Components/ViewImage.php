<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Illuminate\Support\Facades\Storage;

class ViewImage extends Field
{
    protected string $view = 'filament.forms.components.view-image';

    protected ?string $imagePath = null; // e.g. "01KB4N832EJFD0M2AJ2RATQYTC.png"

    protected string $disk = 'local';     // we'll read from local, under app/private

    protected ?string $size = null; // New property for size

    /** Accept the relative path inside "storage/app/private" */
    public function image(string $path): static
    {
        $this->imagePath = ltrim($path, '/');

        return $this;
    }

    /** If you ever want to change disk semantics */
    public function disk(string $disk): static
    {

        $this->disk = $disk;

        return $this;
    }

    public function size(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function getDisk(): string
    {
        return $this->disk;
    }

    public function getSize(): string
    {
        return $this->size ?? '150px';
    }
}
