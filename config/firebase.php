<?php

declare(strict_types=1);

return [
    'default' => env('FIREBASE_PROJECT', 'app'),

    'projects' => [
        'app' => [
            'credentials' => [
                'file' => env('FIREBASE_CREDENTIALS'),
                'auto_discovery' => true,
            ],
            'database' => [
                'auth_variable_override' => [],
            ],
        ],
    ],
];
