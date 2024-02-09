<?php

namespace Modules\Auth\Classes;

use Modules\Auth\Facades\AuthConfig;

class IsEnabledClass
{
    public static function forgotPassword(): bool
    {
        return AuthConfig::forgotPassword();
    }

    public function captcha(): bool
    {
        return AuthConfig::captcha();
    }

    public function avatar(): bool
    {
        return AuthConfig::avatar();
    }

    public function mobileLogin(): bool
    {
        return AuthConfig::includeMobileLogin();
    }

    public function spaLogin(): bool
    {
        return AuthConfig::includeSpaLogin();
    }

    public function register(): bool
    {
        return AuthConfig::register();
    }

    public function verifyUser(): bool
    {
        return AuthConfig::verifyUser();
    }
}
