<?php

namespace Blaze\AdminCore\Providers;

use Blaze\AdminCore\AdminCoreConfiguration;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AdminCoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/admin-core.php', 'admin-core');

        $configurationClass = config('admin-core.configuration', AdminCoreConfiguration::class);

        $this->app->singleton(AdminCoreConfiguration::class, $configurationClass);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load routes, views, and migrations from the package
        if (file_exists(__DIR__.'/../../routes/admin.php')) {
            $this->loadRoutesFrom(__DIR__.'/../../routes/admin.php');
        }

        if (is_dir(__DIR__.'/../../resources/views')) {
            $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin-core');
        }

        if (is_dir(__DIR__.'/../../resources/views/components/admin')) {
            Blade::anonymousComponentPath(__DIR__.'/../../resources/views/components/admin', 'admin');
        }

        if (is_dir(__DIR__.'/../../database/migrations')) {
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        }
    }
}
