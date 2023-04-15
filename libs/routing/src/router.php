<?php 

namespace HexaPHP\Libs\Routing;
use Exception;

class Router
{
    
    private $routes = [];

    public function addRoute($method, $path, $callback){
        $this->routes[$method][$path] = $callback;
    }

    public function getRoutes(){
        return $this->routes;
    }

    public function getRoute($method, $path){
        if (!isset($this->routes[$method][$path])) {
            return null;
        }
        return $this->routes[$method][$path];
    }

    function handle($action, $params = []) {
        
        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }

        $className = $methodName = null;

        if (is_string($action)) {
            [$className, $methodName] = preg_split('/@|::/', $action);
        }
        if (is_array($action)) {
            [$className, $methodName] = array_pad($action, 2, null);
        }

        if ($className && class_exists($className)) {
    
            if (!$methodName) {
                $methodName = '__invoke';
            }
    
            $obj = new $className;
    
            if (!method_exists($obj, $methodName)) {
                throw new Exception("Method not found: {$methodName} in class: {$className}");
            }
    
            return call_user_func_array([$obj, $methodName], $params);
        }
        throw new Exception("Invalid route: {$action}");
    }
}