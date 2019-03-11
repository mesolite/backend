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
        'notification' => [
            'table'      => 'notifications',
            'comment'    => 'Notification',
            'model'      => Railken\Amethyst\Models\Notification::class,
            'schema'     => Railken\Amethyst\Schemas\NotificationSchema::class,
            'repository' => Railken\Amethyst\Repositories\NotificationRepository::class,
            'serializer' => Railken\Amethyst\Serializers\NotificationSerializer::class,
            'validator'  => Railken\Amethyst\Validators\NotificationValidator::class,
            'authorizer' => Railken\Amethyst\Authorizers\NotificationAuthorizer::class,
            'faker'      => Railken\Amethyst\Fakers\NotificationFaker::class,
            'manager'    => Railken\Amethyst\Managers\NotificationManager::class,
            'user'       => App\Models\User::class,
        ],
    ],
];
