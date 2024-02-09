<?php

namespace Modules\Role\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Role\Classes\RoleConfigClass;

class FacadeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(RoleConfigClass::class, function () {
            return new RoleConfigClass();
        });
    }
}
