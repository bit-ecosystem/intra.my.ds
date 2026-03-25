<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Workflows\Schemas;

use App\Filament\Core\Resources\Roles\Schemas\RoleCanView;
use Bites\Workflow\Models\Node;
use Filament\Forms\Components;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;

class WorkflowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // === Workflow details ===
                Components\Select::make('turtle_id')
                    ->relationship('turtle', 'name')
                    ->required()
                    ->searchable(),

                Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Components\Textarea::make('description')
                    ->nullable()
                    ->columnSpanFull(),

                // === STATES (Nodes) ===
                Section::make('States')
                    ->description('Define the workflow states (nodes). Mark exactly one as initial; finals can be one or more.')
                    ->schema([
                        Components\Repeater::make('nodes')
                            ->relationship('nodes') // hasMany on App\Models\Workflow\Workflow
                            ->addActionAlignment(Alignment::Start)
                            ->reorderable()
                            ->defaultItems(0)
                            ->schema([
                                Grid::make(12)->schema([
                                    Components\TextInput::make('name')
                                        ->label('State Name')
                                        ->required()
                                        ->columnSpan(6)
                                        ->maxLength(255),

                                    // Components\Select::make('assignee_role_id')
                                    //     ->label('Assignee Role')
                                    //     ->relationship('assigneeRole', 'name') // belongsTo OrgRole
                                    //     ->searchable()
                                    //     ->preload()
                                    //     ->columnSpan(6),
                                    ...RoleCanView::formComponents(
                                        relationship: 'attachableRoles', // your morphToMany on the model
                                        showSelect: false,               // keep the Select hidden (state updated by the Action)
                                        actionName: 'choose_roles',      // rename if you include twice in same form
                                        superUserRole: 'st_category_A',
                                    ),
                                    Components\Toggle::make('is_initial')
                                        ->inline(false)
                                        ->helperText('Only one state should be initial.')
                                        ->columnSpan(3),

                                    Components\Toggle::make('is_final')
                                        ->inline(false)
                                        ->helperText('Final states end the workflow.')
                                        ->columnSpan(3),

                                    Components\TextInput::make('sort')
                                        ->numeric()
                                        ->default(0)
                                        ->columnSpan(3),
                                ]),
                            ])
                            ->columns(1)
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->minItems(1)
                            ->afterStateUpdated(function (callable $set, callable $get): void {
                                // (Optional) Enforce a single initial state client-side
                                $nodes = $get('nodes') ?? [];
                                $initialCount = collect($nodes)->where('is_initial', true)->count();
                                if ($initialCount > 1) {
                                    // No-op here; let server validation enforce it,
                                    // or write logic to auto-unset other is_initial flags.
                                }

                                $set('nodes', $nodes);
                            }),
                    ])
                    ->collapsible(),

                // === TRANSITIONS ===
                Section::make('Transitions')
                    ->description('Define allowed actions between states. You can add transitions after saving the workflow and states.')
                    ->schema([
                        Components\Repeater::make('transitions')
                            ->relationship('transitions') // hasMany on App\Models\Workflow\Workflow
                            ->addActionAlignment(Alignment::Start)
                            ->reorderable()
                            ->defaultItems(0)
                            ->schema([
                                Grid::make(12)->schema([
                                    Components\Select::make('from_state_id')
                                        ->label('From State')
                                        ->required()
                                        ->searchable()
                                        ->options(function (callable $get, ?Model $model) {
                                            // Prefer persisted nodes of this workflow
                                            if ($model && $model->id) {
                                                return Node::query()
                                                    ->where('workflow_id', $model->id)
                                                    ->orderBy('sort')
                                                    ->pluck('name', 'id');
                                            }

                                            // If creating (no record yet), fallback to in-memory repeater states
                                            $nodes = collect($get('../../nodes') ?? []);

                                            // Provide temporary options by name index would not persist;
                                            // best UX: save the workflow first, then add transitions.
                                            return $nodes->mapWithKeys(fn ($n, $idx): array => [$idx => ($n['name'] ?? 'State #'.($idx + 1))]);
                                        })
                                        ->helperText('Tip: Save the workflow after adding states, then set transitions.')
                                        ->columnSpan(6),

                                    Components\Select::make('to_state_id')
                                        ->label('To State')
                                        ->required()
                                        ->searchable()
                                        ->options(function (callable $get, ?Model $model) {
                                            if ($model && $model->id) {
                                                return Node::query()
                                                    ->where('workflow_id', $model->id)
                                                    ->orderBy('sort')
                                                    ->pluck('name', 'id');
                                            }

                                            $nodes = collect($get('../../nodes') ?? []);

                                            return $nodes->mapWithKeys(fn ($n, $idx): array => [$idx => ($n['name'] ?? 'State #'.($idx + 1))]);
                                        })
                                        ->columnSpan(6),

                                    Components\TextInput::make('action_name')
                                        ->label('Action Name')
                                        ->placeholder('e.g., submit, approve, reject, escalate')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan(8),

                                    Components\TextInput::make('sort')
                                        ->numeric()
                                        ->default(0)
                                        ->columnSpan(4),
                                ]),
                            ])
                            ->columns(1)
                            ->itemLabel(function (?array $state, callable $get): ?string {
                                $from = $state['from_state_id'] ?? null;
                                $to = $state['to_state_id'] ?? null;
                                $action = $state['action_name'] ?? null;

                                return $action ? sprintf('%s (%s → %s)', $action, $from, $to) : null;
                            }),
                    ])
                    ->collapsible(),
            ]);
    }
}
