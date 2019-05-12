<?php

return [
    'api' => [
        'client_id' => env('INSTAGRAM_CLIENT_ID'),
        'redirect_uri' => env('INSTAGRAM_REDIRECT_URI'),
        'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),
    ],

    'crawler' => [
        'username' => env('INSTAGRAM_USERNAME'),
        'password' => env('INSTAGRAM_PASSWORD'),
    ],
];