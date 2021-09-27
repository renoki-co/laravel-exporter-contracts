<?php

namespace RenokiCo\LaravelExporter\Test\Fixtures;

use RenokiCo\LaravelExporter\GaugeMetric;

class TestMetric extends GaugeMetric
{
    /**
     * Assigned value for testing.
     *
     * @var int
     */
    public static $value = 100;

    /**
     * Perform the update call on the collector.
     *
     * @return void
     */
    public function update(): void
    {
        $this->labels(['label' => 'injected-value'])->set(
            value: static::$value,
        );
    }

    /**
     * Get the metric name.
     *
     * @return string
     */
    protected function name(): string
    {
        return 'test_metric';
    }

    /**
     * Define the default labels with their values.
     *
     * @return array
     */
    protected function defaultLabels(): array
    {
        return [
            'label' => 'default-value',
        ];
    }

    /**
     * Get the metric allowed labels.
     *
     * @return array
     */
    protected function allowedLabels(): array
    {
        return [
            'label',
        ];
    }
}
