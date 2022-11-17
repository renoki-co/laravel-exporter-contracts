<?php

namespace RenokiCo\LaravelExporter\Test;

use RenokiCo\LaravelExporter\Exporter;
use RenokiCo\LaravelExporter\Test\Fixtures\GroupedTestMetric;
use RenokiCo\LaravelExporter\Test\Fixtures\OutsideMetric;
use RenokiCo\LaravelExporter\Test\Fixtures\TestMetric;

class MetricTest extends TestCase
{
    public function test_registering_metric(): void
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

    public function test_metrics_modification_without_update(): void
    {
        Exporter::metrics([OutsideMetric::class]);

        Exporter::metric(OutsideMetric::class)->incBy(20);

        $this->assertStringContainsString(
            'laravel_outside_metric{label="default-label"} 20',
            Exporter::exportAsPlainText()
        );

        Exporter::metric(OutsideMetric::class)->incBy(20, ['label' => 'injected-value']);

        $this->assertStringContainsString(
            'laravel_outside_metric{label="injected-value"} 20',
            Exporter::exportAsPlainText()
        );
    }

    public function test_response_with_plain_text(): void
    {
        Exporter::metrics([OutsideMetric::class]);

        Exporter::metric(OutsideMetric::class)->incBy(20);

        $this->assertStringContainsString(
            'laravel_outside_metric{label="default-label"} 20',
            Exporter::exportAsPlainText()
        );

        Exporter::metric(OutsideMetric::class)->incBy(20, ['label' => 'injected-value']);

        Exporter::exportResponse('some-random-text');

        $this->assertStringContainsString(
            'some-random-text',
            Exporter::exportAsPlainText()
        );
    }

    public function test_response_with_plain_text_on_different_group(): void
    {
        Exporter::metrics([OutsideMetric::class]);

        Exporter::metric(OutsideMetric::class)->incBy(20);

        $this->assertStringContainsString(
            'laravel_outside_metric{label="default-label"} 20',
            Exporter::exportAsPlainText()
        );

        Exporter::metric(OutsideMetric::class)->incBy(20, ['label' => 'injected-value']);

        Exporter::exportResponse('some-random-text', 'metrics2');

        $this->assertStringContainsString(
            'laravel_outside_metric{label="default-label"} 20',
            Exporter::exportAsPlainText()
        );

        $this->assertStringContainsString(
            'some-random-text',
            Exporter::exportAsPlainText('metrics2')
        );
    }

    public function test_unknown_group(): void
    {
        $registry = Exporter::run('you-dont-know-me');
        self::assertNotNull($registry);
    }

    public function test_response_with_callable_plain_text(): void
    {
        Exporter::metrics([OutsideMetric::class]);

        Exporter::metric(OutsideMetric::class)->incBy(20);

        $this->assertStringContainsString(
            'laravel_outside_metric{label="default-label"} 20',
            Exporter::exportAsPlainText()
        );

        Exporter::metric(OutsideMetric::class)->incBy(20, ['label' => 'injected-value']);

        $triggered = false;

        Exporter::exportResponse(function () use (&$triggered) {
            $triggered = true;

            return 'some-random-text';
        });

        $this->assertFalse($triggered);

        $this->assertStringContainsString(
            'some-random-text',
            Exporter::exportAsPlainText()
        );

        $this->assertTrue($triggered);
    }
}
