<?php

namespace Modules\Auth\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class ScheduleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->booted(function () {
            // Get Schedule Instance
            $schedule = $this->app->make(Schedule::class);

            // Remove Sanctum Expired Tokens More Than 24 hours
            $schedule->command('sanctum:prune-expired --hours=24')->daily();

            // Remove Expired Password Reset Tokens
            $schedule->command('auth:clear-resets')->hourly();

            // Remove old images that are not related to any model

            $schedule->command('media-library:clean')->weekly();
        });
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        //
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}
