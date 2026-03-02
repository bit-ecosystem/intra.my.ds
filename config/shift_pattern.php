<?php
// config/shift_pattern.php

return [
    // Which pattern is the default when not specified explicitly
    'default' => env('SHIFT_PATTERN_DEFAULT', 'WXYZ'),

    // Multiple named patterns
    'patterns' => [

        // === Existing 24-day W/X/Y/Z ===
        '4G3S' => [
            'anchor_date' => env('SHIFT_ANCHOR_WXYZ', '2026-01-15'), // The Wednesday = Day 1 in your first table
            'timezone'    => env('APP_TIMEZONE', 'Asia/Kuala_Lumpur'),
            'show_rest'   => false,
            'segments'    => [
                ['len' => 6, 'code' => 'M', 'label' => 'Morning',   'start' => '07:00',     'end' => '15:00',      'color' => '#22c55e'],
                ['len' => 2, 'code' => 'R', 'label' => 'Rest',      'start' => null,        'end' => null,         'color' => '#9ca3af'],
                ['len' => 6, 'code' => 'N', 'label' => 'Night',     'start' => '23:00',     'end' => '07:00(+1)',  'color' => '#8b5cf6'],
                ['len' => 2, 'code' => 'R', 'label' => 'Rest',      'start' => null,        'end' => null,         'color' => '#9ca3af'],
                ['len' => 6, 'code' => 'A', 'label' => 'Afternoon', 'start' => '15:00',     'end' => '23:00',      'color' => '#f59e0b'],
                ['len' => 2, 'code' => 'R', 'label' => 'Rest',      'start' => null,        'end' => null,         'color' => '#9ca3af'],
            ],
            'cycle_length' => 24,
            'teams'        => [
                'W' => ['label' => 'Team W', 'offset' => 22, 'color' => '#6b7280'],
                'X' => ['label' => 'Team X', 'offset' => 10, 'color' => '#ef4444'],
                'Y' => ['label' => 'Team Y', 'offset' => 4,  'color' => '#10b981'],
                'Z' => ['label' => 'Team Z', 'offset' => 16, 'color' => '#3b82f6'],
            ],
            
        ],

        // === New 12-day A/B/C ===
        '3G2S' => [
            'anchor_date' => env('SHIFT_ANCHOR_ABC', '2026-01-07'), // The Wednesday = Day 1 in your second table
            'timezone'    => env('APP_TIMEZONE', 'Asia/Kuala_Lumpur'),
            'show_rest'   => false,
            // 12-day sequence: 4N, 2R, 4M, 2R
            'segments'    => [
                ['len' => 4, 'code' => 'N', 'label' => 'Night',     'start' => '19:00',     'end' => '07:00(+1)',  'color' => '#8b5cf6'],
                ['len' => 2, 'code' => 'R', 'label' => 'Rest',      'start' => null,        'end' => null,         'color' => '#9ca3af'],
                ['len' => 4, 'code' => 'M', 'label' => 'Morning',   'start' => '07:00',     'end' => '19:00',      'color' => '#22c55e'],
                ['len' => 2, 'code' => 'R', 'label' => 'Rest',      'start' => null,        'end' => null,         'color' => '#9ca3af'],
            ],
            'cycle_length' => 12,
            'teams'        => [
                'A' => ['label' => 'Team A', 'offset' => 0,  'color' => '#0ea5e9'],
                'B' => ['label' => 'Team B', 'offset' => 11, 'color' => '#f59e0b'],
                'C' => ['label' => 'Team C', 'offset' => 7,  'color' => '#10b981'],
            ],
        ],
    ],
];