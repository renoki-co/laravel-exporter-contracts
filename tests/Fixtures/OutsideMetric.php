<?php

namespace RenokiCo\LaravelExporter\Test\Fixtures;

use RenokiCo\LaravelExporter\CounterMetric;

class OutsideMetric extends CounterMetric
{
    /**
     * Define the default labels with their values.
     *
     * @return array
     */
    protected function defaultLabels(): array
    {
        return [
            'label' => 'default-label',
        ];
    }

    /**
     * Get the metric name.
     *
     * @return string
     */
    protected function name(): string
    {
        return 'outside_metric';
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
