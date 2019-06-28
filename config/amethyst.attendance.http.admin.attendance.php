<?php

return [
    'enabled'    => true,
    'controller' => Railken\Amethyst\Http\Controllers\Admin\AttendancesController::class,
    'router'     => [
        'as'     => 'attendance.',
        'prefix' => '/attendances',
    ],
];
