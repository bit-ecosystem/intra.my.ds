<?php

declare(strict_types=1);

namespace Bites\CorpLogin\Pages;

use Filament\Auth\Pages\Login as LoginBase;

class Login extends LoginBase
{
    public function getLayout(): string
    {
        return 'corp-login::filament.pages.login';
    }
}
