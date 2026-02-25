<?php

namespace App\Filament\Core\Resources\EipProposals\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components;
use Filament\Schemas\Components\Section;

class EipProposalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Proposed by')
                    ->schema([
                        Components\Hidden::make('methodology_id')
                            // ->relationship('methodology', 'methodology')
                            ->label('Methodology'),
                        Components\Select::make('initiator_id')
                            ->disabled()
                            ->relationship('initiator', 'name')
                            ->label('Name')
                            ->placeholder('-')
                            ->preload(),
                        Components\TextInput::make('initiator_sn')->label('Employee ID')->disabled(),
                        Components\TextInput::make('initiator_ou')->label('Department')->disabled(),
                        Components\TextInput::make('extension number'),
                    ]),
                Section::make('Subject')
                    ->schema([
                        Components\DatePicker::make('date')->disabled(),
                        Components\Radio::make('process')->inline()
                            ->options([
                                'fol' => 'FOL',
                                'mol' => 'MOL',
                                'eol' => 'EOL',
                                'plating' => 'Plating',
                                'other' => 'Others',
                            ])->live(),
                            Components\TextInput::make('others')
                            ->visible(fn (callable $get): bool => $get('process') === 'other')
                            ->hiddenLabel()
                            ->required()
                            ->placeholder('Others? Please specify'),
                    ]),
                Components\Select::make('eip_type')
                    ->searchable()
                    ->options([
                        'Quality' => [
                            'q_human_error_reduction' => ' Human Error Reduction',
                            'q_improve_process_job_step' => ' Improve Process/Job Step',
                            'q_quality_up' => ' Quality Up',
                            'q_reduce_defect_rejection_claim' => ' Reduce Defect/Rejection/Claim',
                            'q_improve_defect_detection' => ' Improve Defect Detection',
                            'q_poka-yoke' => ' Poka-Yoke',
                            'q_yield_up' => ' Yield Up',
                        ],
                        'Safety' => [
                            's_ohsas_safety' => ' OHSAS/Safety',
                            's_safety_up' => ' Safety Up',
                            's_security_up' => ' Security Up',
                            's_near_miss' => ' Near Miss',
                            's_unsafe_condition' => ' Unsafe Condition',
                            's_unsafe_act' => ' Unsafe Act',
                        ],
                        'Others' => [
                            'o_5s' => ' 5S',
                            'o_better_work_environment' => ' Better Work Environment',
                            'o_cost_down' => ' Cost Down',
                            'o_effective_communication' => ' Effective Communication',
                            'o_efficiency_up' => ' Efficiency Up',
                            'o_ems' => ' EMS',
                            'o_energy_saving' => ' Energy Saving',
                            'o_image_up' => ' Image Up',
                            'o_improve_capacity' => ' Improve Capacity',
                            'o_improve_material' => ' Improve Material',
                            'o_improve_method' => ' Improve Method',
                            'o_knowledge_up' => ' Knowledge Up',
                            'o_waste_reduction' => ' Waste Reduction',
                            'o_productivity_up' => ' Productivity Up',
                            'o_sales_up' => ' Sales Up',
                            'o_va_cd' => ' VA/CD',
                            'o_others' => ' Others',
                        ],
                    ])

            ]);
    }
}
