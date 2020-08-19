<?php

return [
    'credentials' => [
        'key' => env('GOOGLE_API_KEY', null),
    ],

    'url' => env('GOOGLE_API_URL', 'https://maps.googleapis.com/maps/api/place'),

    'places' => [
        'nearbysearch' => '/nearbysearch/json?',
        'details' => '/details/json?',
    ],
];
