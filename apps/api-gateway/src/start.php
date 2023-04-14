<?php

$app = new \HexaPHP\Libs\Application\App();

$routes = require_once __DIR__ . '/routes.php';

$middlewares = [
    "before" => [

    ],
    "after" => [

    ],
];

$app->pipe($middlewares);

$app->register($routes);

return $app;