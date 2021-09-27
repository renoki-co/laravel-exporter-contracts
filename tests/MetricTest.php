<?php

namespace RenokiCo\LaravelExporter\Test;

use RenokiCo\LaravelExporter\Exporter;
use RenokiCo\LaravelExporter\Test\Fixtures\GroupedTestMetric;
use RenokiCo\LaravelExporter\Test\Fixtures\TestMetric;

class MetricTest extends TestCase
{
    public function test_registering_metric()
    {
        Exporter::register(TestMetric::class);
        Exporter::register(TestMetric::class);

        Exporter::register(GroupedTestMetric::class);
        Exporter::register(GroupedTestMetric::class);

        $this->get(route('laravel-exporter.metrics'))
            ->assertSee('laravel_custom_metric_name_1{label1="some-value"} 100', escape: false);

        Exporter::register(TestMetric::class);
        Exporter::register(TestMetric::class);

        Exporter::register(GroupedTestMetric::class);
        Exporter::register(GroupedTestMetric::class);

        TestMetric::$value = 200;
        GroupedTestMetric::$value = 201;

        $this->get(route('laravel-exporter.metrics', ['group' => 'metrics']))
            ->assertSee('laravel_custom_metric_name_1{label1="some-value"} 200', escape: false)
            ->assertDontSee('laravel_custom_metric_name_2');

        $this->get(route('laravel-exporter.metrics', ['group' => 'new-metrics']))
            ->assertSee('laravel_custom_metric_name_2{label1="some-value-2"} 201', escape: false)
            ->assertDontSee('laravel_custom_metric_name_1');
    }
}
