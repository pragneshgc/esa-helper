<?php

namespace Esa\Helper\Provider;

use Esa\Helper\Modules\Reports\Providers\ReportServiceProvider;
use Illuminate\Support\ServiceProvider;

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
        $this->app->register(ReportServiceProvider::class);
    }
}
