<?php

namespace RenokiCo\LaravelExporter\Test\Fixtures;

use RenokiCo\LaravelExporter\Metric;

class TestMetric extends Metric
{
    /**
     * The collector to store the metric.
     *
     * @var \Prometheus\Gauge
     */
    protected $collector;

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
        $this->collector->set(
            value: static::$value,
            labels: [
                'label1' => 'some-value',
            ],
        );
    }

    /**
     * Register the collector to the registry.
     *
     * @return \Prometheus\Collector
     */
    public function registerCollector()
    {
        return $this->collector = $this->registry->registerGauge(
            namespace: $this->getNamespace(),
            name: 'custom_metric_name', // modify this to be unique,
            help: 'Add a relevant help text information.',
            labels: ['label1'], // optional
        );
    }
}
