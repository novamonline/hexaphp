<?php declare(strict_types = 1);

use HexaPHP\Libs\HttpClient\Request;

define('APP_START', microtime(true));
define('ROOT', realpath(__DIR__ . '/../../'));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
|
*/

require ROOT. '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
| 
| Once we have the application, we can handle the incoming request
|
*/

$app = require_once __DIR__ . '/src/start.php';

$request = $app->request->fromGlobals();

$app->process($request)->send();

define('APP_STOP', microtime(true));