<?php

namespace App\Filament\Core\Resources\EipProposals;

use App\Filament\Core\Resources\EipProposals\Pages\CreateEipProposal;
use App\Filament\Core\Resources\EipProposals\Pages\EditEipProposal;
use App\Filament\Core\Resources\EipProposals\Pages\ListEipProposals;
use App\Filament\Core\Resources\EipProposals\Schemas\EipProposalForm;
use App\Filament\Core\Resources\EipProposals\Tables\EipProposalsTable;
use App\Models\Qas\RunInitiative;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EipProposalResource extends Resource
{
    protected static ?string $model = RunInitiative::class;
    protected static ?string $modelLabel = 'EIP Proposal';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return EipProposalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EipProposalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEipProposals::route('/'),
            'create' => CreateEipProposal::route('/create'),
            'edit' => EditEipProposal::route('/{record}/edit'),
        ];
    }
}
