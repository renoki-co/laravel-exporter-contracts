<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Exporter Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where Exporter will be accessible from.
    | If this setting is null, Exporter will reside under the same domain as
    | the application. Otherwise, this value will serve as the subdomain.
    |
    */

    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | Exporter Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Exporter will be accessible from.
    | Feel free to change this path to anything you like.
    |
    */

    'path' => 'exporter',

    /*
    |--------------------------------------------------------------------------
    | Exporter Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will get attached onto each Exporter route,
    | giving you the chance to add your own middleware to this list or change
    | any of the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middleware' => ['web'],

];
