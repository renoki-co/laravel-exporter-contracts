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
     * @param  \Prometheus\Collector|null  $collector
     * @param  array  $labels
     * @return void
     */
    public function __construct(
        protected CollectorRegistry &$registry,
        protected array $labels = [],
    ) {
        $this->collector = $this->registerCollector();
    }

    /**
     * Get the labels used to mark methods with.
     *
     * @param  array  $labels
     * @return array
     */
    protected function getLabels(array $labels = []): array
    {
        return array_merge($this->defaultLabels(), $labels);
    }

    /**
     * Perform the update call on the collector.
     * Optional, as some metrics can be modified somewhere else.
     *
     * @return void
     */
    public function update(): void
    {
        //
    }

    /**
     * Define the default labels with their values.
     *
     * @return array
     */
    protected function defaultLabels(): array
    {
        return [
            //
        ];
    }

    /**
     * Get the metric name.
     *
     * @return string
     */
    protected function name(): string
    {
        return Str::of(get_class())->snake()->replace('\\', '');
    }

    /**
     * Get the namespace to publish the metric to.
     *
     * @return string
     */
    protected function namespace(): string
    {
        return Str::snake(config('app.name'));
    }

    /**
     * Get the metric help.
     *
     * @return string
     */
    protected function help(): string
    {
        return 'Some default metric help.';
    }

    /**
     * Get the metric allowed labels.
     *
     * @return array
     */
    protected function allowedLabels(): array
    {
        return [
            //
        ];
    }

    /**
     * Proxy call the methods to collector.
     *
     * @param  string  $method
     * @param  array  $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        return $this->collector->{$method}(...$params);
    }

    /**
     * Register the collector to the registry.
     *
     * @return \Prometheus\Collector
     */
    abstract public function registerCollector();
}
