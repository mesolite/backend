<?php

return [
    'http' => [
        'admin' => [
            'router' => [
                'as'         => 'admin.',
                'middleware' => ['auth:api', 'admin'],
                'prefix'     => '/api/admin',
            ],
        ],
        'app' => [
            'router' => [
                'as'         => 'app.',
                'middleware' => ['api'],
                'prefix'     => '/api',
            ],
        ],
        'user' => [
            'router' => [
                'as'         => 'user.',
                'middleware' => ['auth:api'],
                'prefix'     => '/api/user',
            ],
        ],
    ],
];
