<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Contracts\VerifyUser;
use Modules\Auth\Facades\Captcha;
use Modules\Auth\Services\SMSVerifyService;

class AuthModuleServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Auth';

    protected string $moduleNameLower = 'auth';

    protected string $configFileName = 'auth';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            module_path(
                $this->moduleName,
                "Config/$this->configFileName.php"
            ) => config_path($this->configFileName.'.php'),
        ], 'config');

        $this->mergeConfigFrom(
            module_path(
                $this->moduleName,
                "Config/$this->configFileName.php"
            ), $this->configFileName
        );
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(
            array_merge($this->getPublishableViewPaths(), [$sourcePath]),
            $this->moduleNameLower
        );
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (Config::get('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Bind Verify User Service
        $this->app->bind(VerifyUser::class, SMSVerifyService::class);
        // Register Other Providers
        $this->app->register(FacadeServiceProvider::class);
        $this->app->register(ScheduleServiceProvider::class);
        // Add aliases For Facades
        $this->app->alias('Captcha', Captcha::class);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [

        ];
    }
}
