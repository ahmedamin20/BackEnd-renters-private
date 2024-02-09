<?php

namespace Modules\Auth\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Auth\Http\Controllers\CaptchaController;

/**
 * @method static array  getCaptchaErrorMessages() Get Captcha Error Messages For Validation
 * @method static array  getCaptchaValidationRules() Get Captcha Validation Rules
 *
 * @see CaptchaController
 */
class Captcha extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CaptchaController::class;
    }
}
