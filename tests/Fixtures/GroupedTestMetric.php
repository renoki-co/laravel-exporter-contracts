<?php

namespace RenokiCo\LaravelExporter\Test\Fixtures;

use RenokiCo\LaravelExporter\Metric;

class GroupedTestMetric extends Metric
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
     * The group this metric gets shown into.
     *
     * @var string|null
     */
    public static $showsOnGroup = 'new-metrics';

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
                'label1' => 'some-value-2',
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
            name: 'custom_metric_name_2',
            help: 'Add a relevant help text information.',
            labels: ['label1'],
        );
    }
}
