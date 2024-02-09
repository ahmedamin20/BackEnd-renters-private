<?php

namespace Modules\Auth\Enums;

enum SocialAuthEnum
{
    const FACEBOOK = 'facebook';

    const GOOGLE = 'google';

    const TWITTER = 'twitter';

    public static function availableProviders(): array
    {
        return [
            self::FACEBOOK,
            self::GOOGLE,
            self::TWITTER,
        ];
    }
}
