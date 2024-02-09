<?php

namespace Modules\User\Enums;

enum UserTypeEnum
{
    const ADMIN = 'admin';

    const EMPLOYEE = 'employee';

    public static function availableTypes(): array
    {
        return [
            self::ADMIN,
            self::EMPLOYEE,
        ];
    }
}
