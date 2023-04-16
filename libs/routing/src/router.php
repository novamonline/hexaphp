<?php

namespace HexaPHP\Libs\Routing;

use Exception;

class Router
{
    private $routes = [];

    public function addRoute($method, $path, $action)
    {
        $this->routes[$method][$path] = $this->parseAction($action);
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getRoute($method, $path)
    {
        if (!isset($this->routes[$method][$path])) {
            return null;
        }
        return $this->routes[$method][$path];
    }

    public function handle(string $path, $action, array $params = [])
    {
        $action = $this->parseAction($action);

        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }

        [$className, $methodName] = $action;

        if (!class_exists($className)) {
            throw new Exception("Class not found: {$className}");
        }

        $obj = new $className;

        if (!$methodName) {
            $methodName = '__invoke';
        }

        if (!method_exists($obj, $methodName)) {
            throw new Exception("Method not found: {$methodName} in class: {$className}");
        }

        return call_user_func_array([$obj, $methodName], $params);
    }

    private function parseAction($action)
    {
        if (is_string($action)) {
            return preg_split('/@|::/', $action);
        }

        if (is_array($action)) {
            return array_pad($action, 2, null);
        }

        throw new Exception("Invalid action: " . print_r($action, true));
    }
}
