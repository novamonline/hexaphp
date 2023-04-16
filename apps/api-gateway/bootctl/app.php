<?php

use HexaPHP\Libs\Application\Bootstrap;
use HexaPHP\Libs\Application\Container;
use App\Http\Responses\Response;
use App\Http\Requests\Request;
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
    $routes = require_once __DIR__ . '/routes.php';
    $builder = require_once __DIR__ . '/builder.php';
    $configs = require_once __DIR__ . '/configs.php';
    $middlewares = require_once __DIR__ . '/middlewares.php';
    $application->register( 'routes', $routes );
    $application->register( 'builders', $builder );
    $application->register( 'configs', $configs );
    $application->pipe( ...$middlewares );

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