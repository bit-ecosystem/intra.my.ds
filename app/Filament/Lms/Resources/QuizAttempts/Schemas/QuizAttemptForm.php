<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\QuizAttempts\Schemas;

use App\Filament\Forms\Components\ViewImage;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Facades\Auth;

class QuizAttemptForm
{
    protected static $instruction = 'Multiple choice answers';

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Hidden::make('form_id'),
                Forms\Components\Hidden::make('user_id'),
                Forms\Components\Hidden::make('by_staff'),
                Forms\Components\Hidden::make('form_schema'),
                // Forms\Components\Checkbox::make('examiner_style'),
                Forms\Components\Select::make('for_staff')
                    ->relationship('staff', 'name')
                    ->label('Quiz is for Staff Name')
                    ->required()
                    ->visible(fn (callable $get) => $get('examiner_style')),
                Forms\Components\TextInput::make('started_at'),
                Schemas\Components\Section::make(fn (callable $get) => $get('examiner_style') ? null : 'Good Luck, '.Auth::user()->staff->name.'!')
                    ->label('')
                    ->schema(function (callable $get): array {
                        $schema = $get('form_schema');

                        // Handle JSON string or array
                        if (is_string($schema)) {
                            $schema = json_decode($schema, true);
                        }

                        return self::generateDynamicFields($schema);
                    })
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Generate dynamic Filament components based on schema array.
     */
    protected static function generateDynamicFields(?array $schema): array
    {
        if (! $schema) {
            return [];
        }

        $components = [];

        foreach ($schema as $block) {
            $data = $block['data'] ?? [];

            if ($block['type'] === 'quiz') {
                $optionsRaw = $data['options'] ?? [];
                // Count how many correct answers exist
                $correctCount = collect($optionsRaw)
                    ->filter(fn ($opt): bool => (bool) data_get($opt, 'data.correct', false))
                    ->count();
                $options = collect($data['options'] ?? [])
                    ->mapWithKeys(fn ($opt): array => [$opt['data']['key'] => $opt['data']['value']])
                    ->toArray();
                // Build quiz box
                $quizComponents = [];
                if (! empty($data['image'])) {
                    $quizComponents[] = ViewImage::make('quiz_image')
                        ->size('200px')
                        ->image($data['image']);
                }

                if ($correctCount <= 1) {
                    // Add radio if single answer
                    $quizComponents[] = Forms\Components\Radio::make('data.'.$data['name'])
                        ->label($data['label'])
                        ->options($options);
                } else {
                    // Add checkbox list if multiple answers
                    $quizComponents[] = TextEntry::make('multiple')
                        ->hiddenLabel() // hide label
                        ->color('warning')
                        ->badge()
                        ->size(TextSize::Small)
                        ->state(self::$instruction) // the text to show
                        ->dehydrated(false);
                    $quizComponents[] = Forms\Components\CheckboxList::make('data.'.$data['name'])
                        ->label($data['label'])
                        ->options($options);
                }

                $quizComponents[] = TextEntry::make('data.'.$data['description'])
                    ->hiddenLabel() // hide label
                    ->color('info')
                    ->size(TextSize::ExtraSmall)
                    ->state($data['description']) // the text to show
                    ->columnSpanFull()
                    ->dehydrated(false);
                // Wrap in a Card for box effect
                $components[] = Schemas\Components\Section::make($quizComponents)
                    ->columnSpanFull();
            }
        }

        // dd($components);
        return $components;
    }
}
