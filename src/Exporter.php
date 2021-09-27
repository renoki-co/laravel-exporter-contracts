<?php

namespace RenokiCo\LaravelExporter;

use Prometheus\CollectorRegistry;
use Prometheus\Exception\MetricsRegistrationException;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;

class Exporter
{
    /**
     * The metrics to register.
     *
     * @var array
     */
    protected static array $metrics = [
        //
    ];

    /**
     * The registered metrics.
     *
     * @var array
     */
    protected static array $registeredMetrics = [
        //
    ];

    /**
     * The Collector Registries for groups.
     *
     * @var \Prometheus\CollectorRegistry[]
     */
    protected static array $registries = [
        //
    ];

    /**
     * Set the registry.
     *
     * @param  \Prometheus\CollectorRegistry  $collectorRegistry
     * @param  string  $group
     * @return void
     */
    public static function setRegistry(CollectorRegistry $registry, string $group = 'metrics')
    {
        self::$registries[$group] = $registry;
    }

    /**
     * Add a metric to the registrable metrics.
     *
     * @param  string  $class
     * @return void
     */
    public static function register(string $class)
    {
        if (in_array($class, static::$metrics)) {
            return;
        }

        static::$metrics[] = $class;
    }

    /**
     * Set the metrics value.
     *
     * @param  array  $classes
     * @return void
     */
    public static function metrics(array $classes)
    {
        static::$metrics = [];

        foreach ($classes as $class) {
            /** @var string $class */
            static::register($class);
        }
    }

    /**
     * Add the registered metrics to the Prometheus registry.
     *
     * @param  string  $group
     * @return \Prometheus\CollectorRegistry
     */
    public static function run($group = 'metrics')
    {
        foreach (static::$metrics as $metricClass) {
            if (! isset(static::$registries[$metricClass::$showsOnGroup])) {
                static::setRegistry(new CollectorRegistry(new InMemory), $metricClass::$showsOnGroup);
            }

            /** @var \RenokiCo\LaravelExporter\Metric $metric */
            $metric = new $metricClass(
                static::$registries[$metricClass::$showsOnGroup]
            );

            try {
                $metric->registerCollector();
            } catch (MetricsRegistrationException $e) {
                $metric = static::$registeredMetrics[$metricClass];
            }

            /** @var \RenokiCo\LaravelExporter\Metric $metric */
            $metric->update();

            static::$registeredMetrics[$metricClass] = $metric;
        }

        return static::$registries[$group];
    }

    /**
     * Export the metrics as plaintext.
     *
     * @param  string  $group
     * @return string
     */
    public static function exportAsPlainText(string $group = 'metrics'): string
    {
        return (new RenderTextFormat)->render(
            static::run($group)->getMetricFamilySamples()
        );
    }
}
