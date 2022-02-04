<?php

namespace RenokiCo\LaravelExporter\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use RenokiCo\LaravelExporter\Exporter;

abstract class TestCase extends Orchestra
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        Exporter::metrics([]);
        Exporter::flushResponses();
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [
            \RenokiCo\LaravelExporter\LaravelExporterServiceProvider::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'wslxrEFGWY6GfGhvN9L3wH3KSRJQQpBD');
    }
}
