<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Modules\RelationManagers;

use App\Filament\Lms\Resources\QuizAttempts\QuizAttemptResource;
use App\Filament\Lms\Resources\Quizzes\QuizResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class QuizzesRelationManager extends RelationManager
{
    protected static string $relationship = 'quizzes';

    protected static ?string $relatedResource = QuizResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['module_id'] = $this->getOwnerRecord()->getKey(); // the parent Module ID

                        return $data;
                    }),
                CreateAction::make()
                    ->using(function (array $data) {
                        // Ensure module_id is not coming from the client
                        return $this->getRelationship()->create($data);
                    }),
            ])

            ->recordActions([
                // EditAction::make(),
                Actions\Action::make('openDynamicForm')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record): string => QuizAttemptResource::getUrl('create', ['form_id' => $record->id]))
                    // ->openUrlInNewTab() // optional
                    ->label('Do Quiz'),
            ]);
    }

    public static function getTabComponent(Model $module, string $pageClass): Tab
    {
        return Tab::make('Quizzes')
            ->badge($module->quizzes->count())
            ->icon('myicon-quiz')
            ->badgeTooltip('Number of Quizzes');
    }
}
