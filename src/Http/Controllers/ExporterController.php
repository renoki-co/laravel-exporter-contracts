<?php

namespace RenokiCo\LaravelExporter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Prometheus\RenderTextFormat;
use RenokiCo\LaravelExporter\Exporter;

class ExporterController extends Controller
{
    /**
     * Display the Prometheus metrics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $group
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, string $group = 'metrics')
    {
        return response(
            Exporter::exportAsPlainText($group),
            200,
            ['Content-Type' => RenderTextFormat::MIME_TYPE],
        );
    }
}
