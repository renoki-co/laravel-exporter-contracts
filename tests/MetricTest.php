<?php

namespace RenokiCo\LaravelExporter\Test;

use RenokiCo\LaravelExporter\Exporter;
use RenokiCo\LaravelExporter\Test\Fixtures\TestMetric;

class MetricTest extends TestCase
{
    public function test_registering_metric()
    {
        Exporter::register(TestMetric::class);
        Exporter::register(TestMetric::class);

        $this->get(route('laravel-exporter.metrics'))
            ->assertSee('laravel_custom_metric_name{label1="some-value"} 100', escape: false);

        Exporter::register(TestMetric::class);
        Exporter::register(TestMetric::class);

        TestMetric::$value = 200;

        $this->get(route('laravel-exporter.metrics'))
            ->assertSee('laravel_custom_metric_name{label1="some-value"} 200', escape: false);
    }
}
