<?php

namespace App\Filament\Hrm\Resources\Staff\RelationManagers;

use Filament\Tables;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;

class ValidCertificatesRelationManager extends BaseCertificatesRelationManager
{
    protected static ?string $title = 'Valid';

    public function table(Tables\Table $table): Tables\Table
    {
        return parent::table($table)
            ->modifyQueryUsing(
                fn($query) =>
                $query->where('status', 'valid')
            )
            ->emptyStateHeading('No certificates')
            ->emptyStateDescription('No certificates in this category.');;
    }
    
    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        $count = $ownerRecord
            ->certificates()
            ->where('status', 'valid')
            ->count();

        return Tab::make(static::$title. ' Certificates')
            ->badge($count > 0 ? $count : null)
            ->badgeTooltip('Number of valid certificates');
    }

}
