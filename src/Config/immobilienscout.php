<?php

return [
    'service' => [
        'identifier' => env('IMMOSCOUT_IDENTIFIER', null),
        'secret' => env('IMMOSCOUT_SECRET', null),
        'callback_uri' => env('IMMOSCOUT_CALLBACK_URI', null),
        'sandbox' => env('IMMOSCOUT_SANDBOX', true),
    ]
];