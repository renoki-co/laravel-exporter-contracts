<?php

namespace RenokiCo\LaravelExporter\Test;

use RenokiCo\LaravelExporter\Exporter;
use RenokiCo\LaravelExporter\Test\Fixtures\GroupedTestMetric;
use RenokiCo\LaravelExporter\Test\Fixtures\OutsideMetric;
use RenokiCo\LaravelExporter\Test\Fixtures\TestMetric;

class MetricTest extends TestCase
{
    public function test_registering_metric()
    {
        Exporter::metrics([TestMetric::class]);
        Exporter::metrics([TestMetric::class]);

        Exporter::register(GroupedTestMetric::class);
        Exporter::register(GroupedTestMetric::class);

        $this->get(route('laravel-exporter.metrics'))
            ->assertSee('laravel_test_metric{label="injected-value"} 100', escape: false);

        Exporter::register(TestMetric::class);
        Exporter::register(TestMetric::class);

        Exporter::register(GroupedTestMetric::class);
        Exporter::register(GroupedTestMetric::class);

        TestMetric::$value = 200;
        GroupedTestMetric::$value = 201;

        $this->get(route('laravel-exporter.metrics', ['group' => 'metrics']))
            ->assertSee('laravel_test_metric{label="injected-value"} 200', escape: false)
            ->assertDontSee('laravel_grouped_metric');

        $this->get(route('laravel-exporter.metrics', ['group' => 'new-metrics']))
            ->assertSee('laravel_grouped_metric{label="injected-value"} 201', escape: false)
            ->assertDontSee('laravel_test_metric');
    }

    public function test_metrics_modification_without_update()
    {
        Exporter::metrics([OutsideMetric::class]);

        Exporter::metric(OutsideMetric::class)->incBy(20);

        $this->assertStringContainsString(
            'laravel_outside_metric{label="default-label"} 20',
            Exporter::exportAsPlainText()
        );

        Exporter::metric(OutsideMetric::class)
            ->labels(['label' => 'injected-value'])
            ->incBy(20);

        $this->assertStringContainsString(
            'laravel_outside_metric{label="injected-value"} 20',
            Exporter::exportAsPlainText()
        );
    }
}
