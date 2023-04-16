<?php declare(strict_types = 1);

define('APP_START', microtime(true));

$APP = realpath(__DIR__ . '/../');
$ROOT = realpath(__DIR__ . '/../../../');

/*
|--------------------------------------------------------------------------
| Loader
|--------------------------------------------------------------------------
|
| Provides a convenient way to load all the necessary files for the application
|
*/
require_once $ROOT . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
| 
| Once we have the application, we can handle the incoming request
|
*/

$app = require_once $APP . '/bootctl/app.php';

$app()->run();

define('APP_STOP', microtime(true));