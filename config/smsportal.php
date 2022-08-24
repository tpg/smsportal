<?php

declare(strict_types=1);

return [
    'id' => env('SMSPORTAL_ID'),
    'secret' => env('SMSPORTAL_KEY'),
    'endpoint' => env('SMSPORTAL_ENDPOINT', 'https://rest.smsportal.com/v2'),
    'token' => [
        'duration' => 43200,
    ]
];
