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
            'schema'     => Amethyst\Schemas\UserSchema::class,
            'repository' => Amethyst\Repositories\UserRepository::class,
            'serializer' => Amethyst\Serializers\UserSerializer::class,
            'validator'  => Amethyst\Validators\UserValidator::class,
            'authorizer' => Amethyst\Authorizers\UserAuthorizer::class,
            'faker'      => Amethyst\Fakers\UserFaker::class,
            'manager'    => Amethyst\Managers\UserManager::class,
        ],
    ],
];
