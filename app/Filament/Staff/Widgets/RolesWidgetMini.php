<?php

declare(strict_types=1);

namespace App\Filament\Staff\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class RolesWidgetMini extends Widget
{
    protected string $view = 'filament.staff.widgets.roles-widget-mini';

    protected int|string|array $columnSpan = 1;

    protected static ?string $heading = 'Your Roles';

    // Optional: show on dashboard only
    // protected int|string|array $columnSpan = 'full'; // or 1/2/3 etc.

    public function getRoles(): array
    {
        // $user = auth()->user();
        $user = Auth::user();

        return $user->getRoleNames()->toArray() ?? [];
    }
}
