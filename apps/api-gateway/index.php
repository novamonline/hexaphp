<?php declare(strict_types = 1);

use HexaPHP\Libs\Request;

define('APP_START', microtime(true));
define('ROOT', realpath(__DIR__ . '/../../'));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require ROOT. '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__ . '/src/start.php';

$request = Request::fromGlobals();

$response = $app->handle($request);

$kernel->terminate($request, $response);