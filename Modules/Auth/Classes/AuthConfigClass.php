<?php

namespace Modules\Auth\Classes;

use Illuminate\Support\Facades\Config;

class AuthConfigClass
{
    public function authConfigFileName(): string
    {
        return 'auth';
    }

    public function avatar(): bool
    {
        return Config::get($this->authConfigFileName().'.avatar.enabled', false);
    }

    public function captcha(): bool
    {
        return Config::get($this->authConfigFileName().'.enable_captcha', false);
    }

    public function verifyUser(): bool
    {
        return Config::get($this->authConfigFileName().'.enable_verify_user', false);
    }

    public function forgotPassword(): bool
    {
        return config($this->authConfigFileName().'.enable_forgot_password', false);
    }

    public function getRouteName(string $key): string
    {
        return config($this->authConfigFileName().'.routes.names')[$key];
    }

    public function includeMobileLogin(): bool
    {
        return config($this->authConfigFileName().'.include_mobile_login', false);
    }

    public function includeSpaLogin(): bool
    {
        return config($this->authConfigFileName().'.include_spa_login', false);
    }

    public function register(): bool
    {
        return config($this->authConfigFileName().'.include_register', false);
    }

    public function getMiddleware(string $middlewareName = 'verify_email'): ?string
    {
        return config($this->authConfigFileName().'.middleware')[$middlewareName] ?? null;
    }
}
