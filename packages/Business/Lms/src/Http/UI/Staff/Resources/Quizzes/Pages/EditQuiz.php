<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Quizzes\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\Quizzes\QuizResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditQuiz extends EditRecord
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['passing_mark'] /= 100;
        foreach ($data['schema'] as $uuid => $block) {
            if (! Str::startsWith($data['schema'][$uuid]['data']['name'], 'q')) {
                $data['schema'][$uuid]['data']['name'] = 'q'.Str::ulid();
            }

            if (
                isset($block['type']) &&
                $block['type'] === 'quiz' &&
                isset($block['data']['options']) &&
                is_array($block['data']['options'])
            ) {
                foreach (array_keys($block['data']['options']) as $optuuid) {
                    if (! Str::startsWith($block['data']['options'][$optuuid]['data']['key'], 'a')) {
                        $data['schema'][$uuid]['data']['options'][$optuuid]['data']['key'] = 'a'.Str::ulid();
                    }
                }
            }
        }

        return $data;
    }
}
