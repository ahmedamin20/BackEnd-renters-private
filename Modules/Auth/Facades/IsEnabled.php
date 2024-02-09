<?php

namespace Modules\Auth\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Auth\Classes\IsEnabledClass;

/**
 * @method static bool forgotPassword() Determine If Forgot Password Feature Enabled
 * @method static bool verifyUser() Determine If Verify Email Feature Enabled
 * @method static bool captcha() Determine If Google Captcha Feature Enabled
 * @method static bool avatar() Determine If Profile Avatar Enabled
 * @method static bool mobileLogin()
 * @method static bool spaLogin()
 * @method static bool register()
 *
 * @see IsEnabledClass
 */
class IsEnabled extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return IsEnabledClass::class;
    }
}
