<?php

use Illuminate\Support\Facades\Route;
use RenokiCo\LaravelExporter\Http\Controllers\ExporterController;

Route::get('/metrics', ExporterController::class)->name('laravel-exporter.metrics');
