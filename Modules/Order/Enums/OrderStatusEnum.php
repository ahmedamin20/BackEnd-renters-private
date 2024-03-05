<?php

namespace Modules\Order\Enums;

enum OrderStatusEnum
{
    const PENDING = 0;
    const RENTING = 1;
    const REJECTED = 2;
    const FINISHED = 3;
    const CANCELED = 4;

    public static function availableTypes(): array
    {
        return [
            self::PENDING,
            self::RENTING,
            self::REJECTED,
            self::FINISHED,
        ];
    }
}
