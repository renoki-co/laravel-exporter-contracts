<?php

use Illuminate\Support\Facades\Route;
use RenokiCo\LaravelExporter\Http\Controllers\ExporterController;

Route::get('/group/{group?}', ExporterController::class)->name('laravel-exporter.metrics');
