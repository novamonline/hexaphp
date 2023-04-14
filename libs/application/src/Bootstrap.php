<?php 
namespace HexaPHP\Libs\Application;
use HexaPHP\Libs\HttpClient\Response;
use HexaPHP\Libs\HttpClient\Request;
use HexaPHP\Libs\Routing\Router;
use Closure;

class Bootstrap
{
    public Router $router;
    public Request $request;
    public array $routes;
    public Closure $next;

    private array $middlewares;

    public function __construct(Container $container){
        $this->request = new Request();
        $this->router = new Router();
    }
    public function process(Request $request){

        $path = $request->getPathInfo();
        $method = $request->getMethod();

        $action = $this->router->getRoute($method, $path);

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
        $globals = $this->request->fromGlobals();
        return $globals;
    }

    public function register($routes): void{

        foreach($routes as $route => $action){
            [$method, $path] = explode(' ', $route);
            $this->router->addRoute($method, $path, $action);    
        }
    }

    public function pipe($middlewares){
        $this->middlewares = $middlewares;

        foreach($this->middlewares as $when => $action){
            $this->next = fn($request) => $this->router->handle($action);
        }
    }

}