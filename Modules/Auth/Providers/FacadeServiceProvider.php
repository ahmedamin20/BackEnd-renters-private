<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Auth\Classes\AuthConfigClass;
use Modules\Auth\Classes\IsEnabledClass;
use Modules\Auth\Http\Controllers\CaptchaController;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->bind(AuthConfigClass::class, function () {
            return new AuthConfigClass();
        });

        $this->app->bind(CaptchaController::class, function () {
            return new CaptchaController();
        });

        $this->app->bind(IsEnabledClass::class, function () {
            return new IsEnabledClass();
        });
    }
}
