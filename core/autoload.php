<?php

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__) . DS);
}
if (!defined('CORE')) {
    define('CORE', ROOT . 'core' . DS);
}

require_once ROOT . 'vendor' . DS . 'autoload.php';

spl_autoload_register(function ($className) {
    $prefixes = ['HexaPHP\\'];

    foreach ($prefixes as $prefix) {
        if (strpos($className, $prefix) !== 0) {
            continue;
        }

        $relativeClassName = substr($className, strlen($prefix));
        $snakeCaseClassName = preg_replace_callback('/\\B([A-Z])/', function ($matches) {
            return '_' . strtolower($matches[1]);
        }, $relativeClassName);

        $file = ROOT . str_replace('\\', DS, strtolower($snakeCaseClassName)) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

