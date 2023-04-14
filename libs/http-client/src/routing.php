<?php 

namespace HexaPHP\Libs\HttpClient;

class Routing
{
    
        private $routes = [];
    
        public function addRoute($method, $path, $callback){
            $this->routes[$method][$path] = $callback;
        }
    
        public function getRoutes(){
            return $this->routes;
        }
    
        public function getRoute($method, $path){
            return $this->routes[$method][$path];
        }
}