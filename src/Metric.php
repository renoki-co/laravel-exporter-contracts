<?php

namespace RenokiCo\LaravelExporter;

use Illuminate\Support\Str;
use Prometheus\CollectorRegistry;

abstract class Metric
{
    /**
     * The collector to store the metric.
     *
     * @var \Prometheus\Collector
     */
    protected $collector;

    /**
     * The group this metric gets shown into.
     *
     * @var string|null
     */
    public static $showsOnGroup = 'metrics';

    /**
     * Initialize the metric.
     *
     * @param  \Prometheus\CollectorRegistry  $registry
     * @return void
     */
    public function __construct(
        protected CollectorRegistry &$registry,
    ) {
        //
    }

    /**
     * Get the namespace to publish the metric to.
     *
     * @return string
     */
    protected function getNamespace(): string
    {
        return Str::snake(config('app.name'));
    }

    /**
     * Perform the update call on the collector.
     *
     * @return void
     */
    abstract public function update(): void;

    /**
     * Register the collector to the registry.
     *
     * @return \Prometheus\Collector
     */
    abstract public function registerCollector();
}
