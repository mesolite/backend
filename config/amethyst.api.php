<?php

return [
    'http' => [
        'app' => [
            'router' => [
                'as'         => 'app.',
                'middleware' => [
                	'optional-auth:api',
                    'permission'
                ],
                'prefix'     => '/api',
            ],
        ],
        'data' => [
            'router' => [
                'as'         => 'data.',
                'middleware' => [
                    'optional-auth:api',
                    'permission'
                ],
                'prefix'     => '/api/data',
            ],
        ],
    ],
];
