<?php

declare(strict_types=1);

namespace Bites\FilamentBlueprints;

use Bites\FilamentBlueprints\Contracts\BlockContract;
use Filament\Forms\Components\Builder\Block;

final class BlockRegistry
{
    /** @var array<string, BlockContract> */
    private array $byName = [];

    /**
     * @param  iterable<BlockContract>  $services
     */
    public function __construct(iterable $services)
    {
        foreach ($services as $service) {
            $this->byName[$service::name()] = $service;
        }
    }

    /** @return Block[] */
    public function paletteBlocks(): array
    {
        return array_values(array_map(fn (BlockContract $svc): Block => $svc->palette(), $this->byName));
    }

    /** @return array of decoded Filament components */
    public function decodeBuilder(?array $state): array
    {
        $state = $state ?? [];
        $out = [];

        foreach ($state as $item) {
            $type = $item['type'] ?? null;
            $payload = isset($item['data']) ? (array) $item['data'] : $item;

            if (! $type || ! isset($this->byName[$type])) {
                continue;
            }

            $decoded = $this->byName[$type]->decode($payload, $this);

            if (is_array($decoded)) {
                array_push($out, ...$decoded);
            } elseif ($decoded !== null) {
                $out[] = $decoded;
            }
        }

        return $out;
    }
}
