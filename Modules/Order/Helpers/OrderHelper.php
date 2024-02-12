<?php

namespace Modules\Order\Helpers;

class OrderHelper
{
    public static function canceledStatus(): string
    {
        return '0';
    }

    public static function pendingStatus(): string
    {
        return '1';
    }

    public static function approvedStatus(): string
    {
        return '2';
    }

    public static function getTranslatedStatus(int $status): string
    {

        return static::availableStates()[$status];
    }

    public static function availableStates(): array
    {
        return [
            static::pendingStatus() => 'pending',
            static::approvedStatus() => 'approved',
            static::canceledStatus() => 'canceled',
        ];
    }
}
