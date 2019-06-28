<?php

return [
    'table'      => 'amethyst_attendances',
    'comment'    => 'Attendance',
    'model'      => App\Models\Attendance::class,
    'schema'     => App\Schemas\AttendanceSchema::class,
    'repository' => Railken\Amethyst\Repositories\AttendanceRepository::class,
    'serializer' => Railken\Amethyst\Serializers\AttendanceSerializer::class,
    'validator'  => Railken\Amethyst\Validators\AttendanceValidator::class,
    'authorizer' => Railken\Amethyst\Authorizers\AttendanceAuthorizer::class,
    'faker'      => Railken\Amethyst\Fakers\AttendanceFaker::class,
    'manager'    => Railken\Amethyst\Managers\AttendanceManager::class,
];
