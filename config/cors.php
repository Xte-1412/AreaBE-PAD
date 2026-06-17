<?php

return [

    'paths' => ['api/*', '*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'https://zona-fe-pad.vercel.app',
        'https://area-fe-pad.vercel.app',
        'https://talcum-fragile-panic.ngrok-free.dev',
        'http://10.33.35.48:3000',
        'http://202.43.94.30:3000',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];