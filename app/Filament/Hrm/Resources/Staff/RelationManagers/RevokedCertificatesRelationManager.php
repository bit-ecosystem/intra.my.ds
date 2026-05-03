<?php

namespace App\Filament\Hrm\Resources\Staff\RelationManagers;

use Filament\Tables;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;

class RevokedCertificatesRelationManager extends BaseCertificatesRelationManager
{
    protected static ?string $title = 'Revoked';

    public function table(Tables\Table $table): Tables\Table
    {
        return parent::table($table)
            ->modifyQueryUsing(
                fn($query) =>
                $query->where('status', 'revoked')
            )
            ->emptyStateHeading('No revoked certificates')
            ->emptyStateDescription('Staff has no revoked certificates.');
    }
    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        $count = $ownerRecord
            ->certificates()
            ->where('status', 'revoked')
            ->count();

        return Tab::make(static::$title. ' Certificates')
            ->badge($count > 0 ? $count : null)
            ->badgeColor('danger')
            ->badgeTooltip('Number of revoked certificates');
    }
}
