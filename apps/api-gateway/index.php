<?php declare(strict_types = 1);

/*
|--------------------------------------------------------------------------
| Loader
|--------------------------------------------------------------------------
|
| Provides a convenient way to load all the necessary files for the application
|
*/

require_once __DIR__. '/src/builder.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
| 
| Once we have the application, we can handle the incoming request
|
*/

$app = require_once __DIR__ . '/src/start.php';

$app->run();

define('APP_STOP', microtime(true));