<?php
namespace HexaPHP\Libs\Application;

use HexaPHP\Libs\HttpClient\Request;
use HexaPHP\Libs\HttpClient\Response;
use HexaPHP\Libs\Routing\Router;
use Closure;
use Psr\Container\ContainerInterface;

class Bootstrap
{
    private ContainerInterface $container;
    private Router $router;
    private Request $request;
    private Closure $next;
    private array $middlewares;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->router = $container->get('router');
        $this->request = $container->get('request');
    }

    public function process(Request $request): Response
    {
        $path = $request->getPathInfo();
        $method = $request->getMethod();

        $action = $this->router->getRoute($method, $path);

        $content = $this->router->handle($path, $action);

        return $this->response($content);
    }

    public function response(mixed $content): Response
    {
        $headers = $this->request->headers->all();

        if (is_array($content)) {
            $content = json_encode($content);
            $headers['Content-Type'] = 'application/json';
        }

        return new Response($content, 200, $headers);
    }

    public function requestGlobals()
    {
        $globals = $this->request->fromGlobals();
        return $globals;
    }

    public function register($routes): self
    {
        foreach ($routes as $route => $action) {
            [$method, $path] = array_pad(explode(' ', $route), 2, null);
            $this->router->addRoute($method, $path, $action);
        }

        return $this;
    }

    public function pipe($pipes): self
    {
        $this->middlewares = $pipes['middlewares'] ?? [];

        foreach ($this->middlewares as $when => $action) {
            $this->next = fn ($request) => $this->router->handle("middleware", $action, [$request]);
        }

        return $this;
    }
}
