<?php

namespace App\Filament\Hrm\Resources\Staff\RelationManagers;

use Filament\Tables;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;

class ExpiredCertificatesRelationManager extends BaseCertificatesRelationManager
{
    protected static ?string $title = 'Expired';

    public function table(Tables\Table $table): Tables\Table
    {
        return parent::table($table)
            ->modifyQueryUsing(fn ($query) =>
                $query->where('status', 'expired')
            )
            ->emptyStateHeading('No expired certificates')
            ->emptyStateDescription('Staff has no expired certificates.');
    }
        public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        $count = $ownerRecord
            ->certificates()
            ->where('status', 'expired')
            ->count();

        return Tab::make(static::$title. ' Certificates')
            ->badge($count > 0 ? $count : null)
            ->badgeColor('gray')
            ->badgeTooltip('Number of expired certificates');
    }
}