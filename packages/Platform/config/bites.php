<?php

declare(strict_types=1);

return [
    'sync' => ['queue' => false],
    'ui' => [
        'packages' => [
            'platform\src\Auth' => 'Bites\Platform\Auth',
            'platform\src\Branding' => 'Bites\Platform\Branding',
            'platform\src\Sync' => 'Bites\Platform\Sync',
            'platform\src\UI' => 'Bites\Platform\UI',
        ],
        'panels' => [
            // panel_id, path, color, brandName, homeUrl
            'admin' => ['admin', 'admin', '#7f174b', 'Administration', 'filament.admin.pages.dashboard'],
            'staff' => ['staff', 'staff', '#09829f', 'ATM Staff Intranet', 'filament.staff.pages.dashboard'],
        ],
    ],
];
