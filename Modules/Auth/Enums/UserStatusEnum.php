<?php

namespace Modules\Auth\Enums;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

enum UserStatusEnum
{
    const ACTIVE = true;

    const INACTIVE = false;

    public static function isActive(User $user): bool
    {
        return $user->status == self::ACTIVE;
    }

    public static function isInActive(User|Authenticatable $user): bool
    {
        info($user);

        return $user->status == self::INACTIVE;
    }

    public static function availableNumericStatuses(): array
    {
        return [self::ACTIVE, self::INACTIVE];
    }

    final public static function accountMustBeActiveMiddleware(): string
    {
        return 'account_must_be_active';
    }
}
