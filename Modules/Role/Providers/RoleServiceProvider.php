<?php

namespace Modules\Role\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Role';

    protected string $configFileName = 'permission';

    protected string $moduleNameLower = 'role';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(
            module_path($this->moduleName, 'Database/Migrations')
        );
        //        if(Role::registerSpatieMiddlewares()){
        //            $router = $this->app->make(Router::class);
        //
        //            $router->aliasMiddleware(
        //                Role::getSpatieMiddleware('role') ,
        //                RoleMiddleware::class
        //            );
        //            $router->aliasMiddleware(
        //                Role::getSpatieMiddleware('permission') ,
        //                PermissionMiddleware::class
        //            );
        //            $router->aliasMiddleware(
        //                Role::getSpatieMiddleware('role_or_permission') ,
        //                RoleOrPermissionMiddleware::class
        //            );
        //        }

        //! This Gate does not support multiple permissions names

    }

    public function register(): void
    {

        $this->app->register(FacadeServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->configFileName.'.php'),
        ], 'config');

        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->configFileName
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
            array_merge(
                $this->getPublishableViewPaths(),
                [$sourcePath]
            ),
            $this->moduleNameLower
        );
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'));
        }
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
}
