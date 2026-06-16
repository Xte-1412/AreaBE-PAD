<?php

return [

    'paths' => ['api/*', '*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'https://zona-fe-pad.vercel.app',
        'https://area-fe-pad.vercel.app',
        'https://talcum-fragile-panic.ngrok-free.dev',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];