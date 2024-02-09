<?php

namespace Modules\Auth\Enums;

enum AuthEnum
{
    const UNIQUE_COLUMN = 'email';

    const VERIFIED_AT = 'email_verified_at';

    const AVATAR_COLLECTION_NAME = 'avatar';

    const AVATAR_RELATIONSHIP_NAME = 'avatar';

    public static function isUniqueKeyEmail(): bool
    {
        return self::UNIQUE_COLUMN == 'email';
    }

    public static function fakeGarageManagerId(): int
    {
        return 2;
    }

    public static function fake(): int
    {
        return 5;
    }
}
