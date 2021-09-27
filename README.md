Laravel Prometheus Exporter
===========================

![CI](https://github.com/renoki-co/laravel-exporter-contracts/workflows/CI/badge.svg?branch=master)
[![codecov](https://codecov.io/gh/renoki-co/laravel-exporter-contracts/branch/master/graph/badge.svg)](https://codecov.io/gh/renoki-co/laravel-exporter-contracts/branch/master)
[![StyleCI](https://github.styleci.io/repos/410802300/shield?branch=master)](https://github.styleci.io/repos/410802300)
[![Latest Stable Version](https://poser.pugx.org/renoki-co/laravel-exporter-contracts/v/stable)](https://packagist.org/packages/renoki-co/laravel-exporter-contracts)
[![Total Downloads](https://poser.pugx.org/renoki-co/laravel-exporter-contracts/downloads)](https://packagist.org/packages/renoki-co/laravel-exporter-contracts)
[![Monthly Downloads](https://poser.pugx.org/renoki-co/laravel-exporter-contracts/d/monthly)](https://packagist.org/packages/renoki-co/laravel-exporter-contracts)
[![License](https://poser.pugx.org/renoki-co/laravel-exporter-contracts/license)](https://packagist.org/packages/renoki-co/laravel-exporter-contracts)

Base contracts implementation for Prometheus exports in Laravel.

## 🤝 Supporting

If you are using one or more Renoki Co. open-source packages in your production apps, in presentation demos, hobby projects, school projects or so, spread some kind words about our work or sponsor our work via Patreon. 📦

You will sometimes get exclusive content on tips about Laravel, AWS or Kubernetes on Patreon and some early-access to projects or packages.

[<img src="https://c5.patreon.com/external/logo/become_a_patron_button.png" height="41" width="175" />](https://www.patreon.com/bePatron?u=10965171)

## 🚀 Installation

You can install the package via composer:

```bash
composer require renoki-co/laravel-exporter-contracts
```

Publish the config:

```bash
$ php artisan vendor:publish --provider="RenokiCo\LaravelExporter\LaravelExporterServiceProvider" --tag="config"
```

## 🙌 Usage

All you have to do is to create a `\RenokiCo\LaravelExporter\Metric` class that defines how the values will update on each Prometheus call to scrap, and the definition of the collector.

The package will register a `/exporter/metrics` endpoint and you can point Prometheus towards it for scraping.

```php
use RenokiCo\LaravelExporter\Metric;

class CustomMetric extends Metric
{
    /**
     * The collector to store the metric.
     *
     * @var \Prometheus\Gauge
     */
    protected $collector;

    /**
     * Perform the update call on the collector.
     *
     * @return void
     */
    public function update(): void
    {
        $this->collector->set(
            value: mt_rand(0, 100),
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
```

In your `AppServiceProvider`'s `boot()` method, register your metric:

```php
use RenokiCo\LaravelExporter\LaravelExporter;

class AppServiceProvider extends ServiceProvider
{
    // ...

    public function boot()
    {
        LaravelExporter::register(CustomMetric::class);
    }
}
```

## 🐛 Testing

``` bash
vendor/bin/phpunit
```

## 🤝 Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## 🔒  Security

If you discover any security related issues, please email alex@renoki.org instead of using the issue tracker.

## 🎉 Credits

- [Alex Renoki](https://github.com/rennokki)
- [All Contributors](../../contributors)
