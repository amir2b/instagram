<?php

return [
    'api' => [
        'client_id' => env('INSTAGRAM_CLIENT_ID'),
        'redirect_uri' => env('INSTAGRAM_REDIRECT_URI'),
        'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),
    ],

    'web' => [
        'user_id' => env('INSTAGRAM_USER_ID'),
        'username' => env('INSTAGRAM_USERNAME'),
        'password' => env('INSTAGRAM_PASSWORD'),
    ],

    'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36',
];