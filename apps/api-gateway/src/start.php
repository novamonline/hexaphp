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
$container = new Container();
$app = new Bootstrap($container);

/*
|--------------------------------------------------------------------------
| Set up pipelines
|--------------------------------------------------------------------------
| 
| Once we have the application, we can handle the incoming request
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
$app->register($routes);
$app->pipe($middlewares);

/*
|--------------------------------------------------------------------------
| Process the request
|--------------------------------------------------------------------------
| 
| Once we have the application, we can handle the incoming request
|
*/
global $request;
$request = $app->globals();
return $app->process($request);