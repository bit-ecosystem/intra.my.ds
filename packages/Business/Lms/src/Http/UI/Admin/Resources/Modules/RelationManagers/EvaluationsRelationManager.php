<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Modules\RelationManagers;

use Bites\Business\Lms\Http\UI\Admin\Resources\Evaluations\EvaluationResource;
use Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\FeedbackResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EvaluationsRelationManager extends RelationManager
{
    protected static string $relationship = 'quizzes';

    protected static ?string $relatedResource = EvaluationResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['module_id'] = $this->getOwnerRecord()->getKey(); // the parent Module ID

                        return $data;
                    }),
            ])

            ->recordActions([
                // EditAction::make(),
                // Actions\Action::make('openDynamicForm')
                //     ->icon('heroicon-o-arrow-top-right-on-square')
                //     ->url(fn ($record): string => FeedbackResource::getUrl('create', ['form_id' => $record->id]))
                //     // ->openUrlInNewTab() // optional
                //     ->label('Give Feedback'),
            ]);
    }

    public static function getTabComponent(Model $module, string $pageClass): Tab
    {
        return Tab::make('Feedback')
            // ->badge($module->quizzes->count())
            ->icon('heroicon-o-hand-thumb-up');
        // ->badgeTooltip('Number of Quizzes');
    }
}
