<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\Tokens\Schemas;

use Filament\Schemas\Schema;

class TokenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
