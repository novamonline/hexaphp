<?php 
namespace HexaPHP\Libs\Application;
use HexaPHP\Libs\HttpClient\Response;
use HexaPHP\Libs\HttpClient\Request;
use HexaPHP\Libs\Routing\Router;

class App
{
    public Router $router;
    public Request $request;
    public array $routes;

    private $middlewares;

    public function __construct(){
        $this->request = new Request();
        $this->router = new Router();
    }
    public function process(Request $request){

        $path = $request->getPathInfo();
        $method = $request->getMethod();

        $action = $this->router->getRoute($method, $path);

        if (!$action){
            return new Response(
                'Not Found',
                404,
                $request->headers->all(),
            );
        }

        $content = $this->router->handle($action);

        return $this->response($content);
    }

    public function response(mixed $content) {

        $headers = $this->request->headers->all();
        
        if (is_array($content)) {
            $content = json_encode($content);
            $headers['Content-Type'] = 'application/json';
        }

        return new Response( $content, 200, $headers);
    }

    public function globals(){
        return $this->request->fromGlobals();
    }

    public function register($routes){

        foreach($routes as $route => $action){
            [$method, $path] = explode(' ', $route);
            $this->router->addRoute($method, $path, $action);    
        }
    }

    public function pipe($middlewares){
        $this->middlewares = $middlewares;
    }

}