<?php

namespace RenokiCo\LaravelExporter;

abstract class CounterMetric extends Metric
{
    /**
     * The collector to store the metric.
     *
     * @var \Prometheus\Counter
     */
    protected $collector;

    /**
     * Increment by a specific count.
     *
     * @param  int|float  $count
     * @param  array  $labels
     * @return mixed[]
     */
    public function incBy($count, array $labels = [])
    {
        return $this->collector->incBy($count, $this->getLabels($labels));
    }

    /**
     * Register the collector to the registry.
     *
     * @return \Prometheus\Counter
     */
    public function registerCollector()
    {
        return $this->registry->registerCounter(
            namespace: $this->namespace(),
            name: $this->name(),
            help: $this->help(),
            labels: $this->allowedLabels(),
        );
    }
}
