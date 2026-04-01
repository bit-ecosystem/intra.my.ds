<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Arr;

class QuizScoringService
{
    public function scoreQuiz(array $schema, array $entry): array
    {
        $answerKey = [];
        $labels = [];

        foreach ($schema as $block) {
            if (($block['type'] ?? null) !== 'quiz') {
                continue;
            }

            $data = $block['data'] ?? [];
            $name = $data['name'] ?? null;
            if (! $name) {
                continue;
            }

            $labels[$name] = $data['label'] ?? null;

            $correctKeys = [];
            foreach ($data['options'] ?? [] as $opt) {
                $optData = $opt['data'] ?? [];
                if (! empty($optData['correct'])) {
                    $correctKeys[] = $optData['key'];
                }
            }

            $answerKey[$name] = $correctKeys;
        }

        $results = [];
        $total = 0;
        $max = count($answerKey);

        foreach ($answerKey as $qName => $correctKeys) {
            $submitted = Arr::get($entry, $qName, []);
            if (! is_array($submitted)) {
                $submitted = [$submitted];
            }

            // Sort both arrays for order-insensitive comparison
            sort($submitted);
            $correctCopy = $correctKeys;
            sort($correctCopy);

            // ✅ Exact match required: all correct answers must be selected, no extras
            $isCorrect = ($submitted === $correctCopy);

            if ($isCorrect) {
                ++$total;
            }

            $results[$qName] = [
                'label' => $labels[$qName] ?? null,
                'submitted' => $submitted,
                'correct' => $correctKeys,
                'is_correct' => $isCorrect,
            ];
        }

        return [
            'total' => $total,
            'max' => $max,
            'results' => $results,
        ];
    }
}
