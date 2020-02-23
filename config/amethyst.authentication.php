<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Entity
    |--------------------------------------------------------------------------
    |
    | Here you may configure the entity user used for authentication.
    |
    */
    'entity' => App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Http configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the routes
    |
    */
    'http' => [
        'app' => [
            'authentication' => [
                'enabled'    => true,
                'controller' => Amethyst\Http\Controllers\App\AuthController::class,
                'router'     => [
                    'as'     => 'auth.',
                    'prefix' => '/auth',
                ],
            ],
        ],
    ],
];
