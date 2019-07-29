<?php

return [
    'enabled'    => true,
    'controller' => Amethyst\Http\Controllers\Admin\AttendancesController::class,
    'router'     => [
        'as'     => 'attendance.',
        'prefix' => '/attendances',
    ],
];
