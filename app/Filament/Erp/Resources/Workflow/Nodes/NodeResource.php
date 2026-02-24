<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Nodes;

use App\Filament\Erp\Resources\Workflow\Nodes\Pages\CreateNode;
use App\Filament\Erp\Resources\Workflow\Nodes\Pages\EditNode;
use App\Filament\Erp\Resources\Workflow\Nodes\Pages\ListNodes;
use App\Filament\Erp\Resources\Workflow\Nodes\Schemas\NodeForm;
use App\Filament\Erp\Resources\Workflow\Nodes\Tables\NodesTable;
use App\Models\Workflow\Node;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class NodeResource extends Resource
{
    protected static ?string $model = Node::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-w-node';

    protected static ?int $navigationSort = 53;

    protected static string|UnitEnum|null $navigationGroup = 'Process Management';

    public static function form(Schema $schema): Schema
    {
        return NodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NodesTable::configure($table);
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
            'index' => ListNodes::route('/'),
            'create' => CreateNode::route('/create'),
            'edit' => EditNode::route('/{record}/edit'),
        ];
    }
}
