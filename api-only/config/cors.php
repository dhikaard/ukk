<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'v1/rent/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:8080'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
