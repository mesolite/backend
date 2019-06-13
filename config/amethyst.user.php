<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    |
    | Here you can change the table name and the class components.
    |
    */
    'data' => [
        'user' => [
            'table'      => 'amethyst_users',
            'comment'    => 'User',
            'model'      => App\Models\User::class,
            'schema'     => Railken\Amethyst\Schemas\UserSchema::class,
            'repository' => Railken\Amethyst\Repositories\UserRepository::class,
            'serializer' => Railken\Amethyst\Serializers\UserSerializer::class,
            'validator'  => Railken\Amethyst\Validators\UserValidator::class,
            'authorizer' => Railken\Amethyst\Authorizers\UserAuthorizer::class,
            'faker'      => Railken\Amethyst\Fakers\UserFaker::class,
            'manager'    => Railken\Amethyst\Managers\UserManager::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Http configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the routes
    |
    */
    'http' => [
        'admin' => [
            'user' => [
                'enabled'    => true,
                'controller' => Railken\Amethyst\Http\Controllers\Admin\UsersController::class,
                'router'     => [
                    'as'     => 'user.',
                    'prefix' => '/users',
                ],
            ],
        ],
    ],
];
