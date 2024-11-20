<?php

namespace Esa\Helper\Provider;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'esa');
        $this->publishes([
            __DIR__ . '/../../dist' => public_path('vendor/esa')
        ], 'esa-helper');
    }
}
