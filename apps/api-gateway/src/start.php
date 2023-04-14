<?php

use HexaPHP\Libs\Application\Bootstrap;
use HexaPHP\Libs\Application\Container;
use HexaPHP\Libs\HttpClient\Request;
use HexaPHP\Libs\HttpClient\Response;

$routes = require_once __DIR__ . '/routes.php';

$container = new Container();

$app = new Bootstrap($container);

$middlewares = [
    "before" => [

    ],
    "after" => [

    ],
];

$app->pipe($middlewares);

$app->register($routes);

$request = $app->globals();

return $app->process($request);