<?php

use HexaPHP\Libs\Application\Bootstrap;
use HexaPHP\Libs\Application\Container;
use HexaPHP\Libs\HttpClient\Request;
use HexaPHP\Libs\HttpClient\Response;

/*
|--------------------------------------------------------------------------
| Load the app
|--------------------------------------------------------------------------
| 
| Once we have the application, we can handle the incoming request
|
*/
global $container;
$app = new Bootstrap( $container = new Container() );

/*
|--------------------------------------------------------------------------
| Set up pipelines
|--------------------------------------------------------------------------
| 
| We can set up the pre and post request pipelines for the application
|
*/
global $middlewares;
$middlewares = [
    "before" => [

    ],
    "after" => [

    ],
];

/*
|--------------------------------------------------------------------------
| Register routes and middlewares
|--------------------------------------------------------------------------
| 
| Once we have the application, we can handle the incoming request
|
*/
global $routes;
$routes = require_once __DIR__ . '/routes.php';
$app->register($routes)->pipe($middlewares);

/*
|--------------------------------------------------------------------------
| Process the request
|--------------------------------------------------------------------------
| 
| Once we have the application, we can handle the incoming request
|
*/
global $request;
return $app->process( $request = $app->globals() );