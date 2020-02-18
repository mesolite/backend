<?php

return [
    'http' => [
        'app' => [
            'router' => [
                'as'         => 'app.',
                'middleware' => [
                	'optional-auth:api',
                ],
                'prefix'     => '/api',
            ],
        ],
        'data' => [
            'router' => [
                'as'         => 'data.',
                'middleware' => [
                    'optional-auth:api',
                ],
                'prefix'     => '/api/data',
            ],
        ],
    ],
];
