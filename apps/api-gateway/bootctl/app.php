<?php

use HexaPHP\Libs\Application\Bootstrap;
use HexaPHP\Libs\Application\Container;
use HexaPHP\Libs\HttpClient\Request;
use HexaPHP\Libs\HttpClient\Response;
use HexaPHP\Libs\Routing\Router;

/*
|--------------------------------------------------------------------------
| Load the app
|--------------------------------------------------------------------------
| 
| Once we have the application, we can handle the incoming request
|
*/
global $container;
global $middlewares;
global $routes;
global $request;


return function() {
    /*
    |--------------------------------------------------------------------------
    | Ignition
    |--------------------------------------------------------------------------
    | 
    | We can set up the application engines
    |
    */
    $container = new Container();
    $container->bind('router', Router::class);
    $container->bind('request', Request::class);
    $container->bind('response', Response::class);
    $application = new Bootstrap( $container );

    /*
    |--------------------------------------------------------------------------
    | Register routes and middlewares
    |--------------------------------------------------------------------------
    | 
    | Once we have the application, we can handle the incoming request
    |
    */
    $pipes = require_once __DIR__ . '/builder.php';
    $routes = require_once __DIR__ . '/routes.php';
    $application->register( $routes )->pipe( $pipes );

    /*
    |--------------------------------------------------------------------------
    | Process the request
    |--------------------------------------------------------------------------
    | 
    | Once we have the application, we can handle the incoming request
    |
    */
    $request = $application->requestGlobals();
    return $application->process( $request ); 
};