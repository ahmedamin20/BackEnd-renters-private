<?php

namespace Modules\Auth\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Auth\Classes\AuthConfigClass;

/**
 * @method static bool avatar()
 * @method static bool captcha()
 * @method static bool verifyUser()
 * @method static bool forgotPassword()
 * @method static string authConfigFileName()
 * @method static string getRouteName(string $key) Returns Config Route Name
 * @method static bool includeMobileLogin() Get includeMobileLogin Config Value
 * @method static bool includeSpaLogin() Get includeSpaLogin Config Value
 * @method static bool register() Get Register Config Value
 * @method static bool|null getMiddleware(string $middlewareName = 'verify_email') Get Middleware Name
 *
 * @see AuthConfigClass
 */
class AuthConfig extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AuthConfigClass::class;
    }
}
