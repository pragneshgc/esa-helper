<?php

namespace Esa\Helper\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Auth\Providers\AuthServiceProvider;
use Modules\Reports\Providers\ReportServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'esa');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->publishes([
            __DIR__ . '/../../dist' => public_path('vendor/esa')
        ], 'esa-helper');

        $this->registerProviders();
    }

    private function registerProviders()
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(ReportServiceProvider::class);
    }
}
