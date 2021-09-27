<?php

namespace RenokiCo\LaravelExporter;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;

class LaravelExporterServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/exporter.php' => config_path('exporter.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/exporter.php', 'exporter'
        );

        Exporter::setRegistry(new CollectorRegistry(new InMemory), group: 'metrics');

        Route::group([
            'domain' => config('exporter.domain', null),
            'prefix' => config('exporter.path'),
            'middleware' => config('exporter.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
