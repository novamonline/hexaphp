<?php
namespace HexaPHP\Libs\Application;

use HexaPHP\Libs\HttpClient\IRequest;
use HexaPHP\Libs\HttpClient\IResponse;
use HexaPHP\Libs\Routing\Router;
use Closure;
use Psr\Container\ContainerInterface;

class Bootstrap
{
    private ContainerInterface $container;
    private Router $router;
    private IRequest $request;
    private IResponse $response;
    private Closure $next;
    private array $middlewares;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->router = $container->get('router');
        $this->request = $container->get('request');
        $this->response = $container->get('response');
    }

    public function process(IRequest $request): IResponse
    {
        $path = $request->getPathInfo();
        $method = $request->getMethod();

        $action = $this->router->getRoute($method, $path);

        $content = $this->router->handle($path, $action);

        return $this->response($content);
    }

    public function response(mixed $content): StreamInterface
    {
        $headers = $this->request->headers->all();

        if (is_array($content)) {
            $content = json_encode($content);
            $headers['Content-Type'] = 'application/json';
        }

        return $this->response->getBody($content, 200, $headers);
    }

    public function requestGlobals()
    {
        $globals = $this->request->fromGlobals();
        return $globals;
    }

    public function register(string $type, array $registrants): self
    {
        switch ($type) {
            case 'routes':
                foreach ($registrants as $route => $action) {
                    [$method, $path] = array_pad(explode(' ', $route), 2, null);
                    $this->router->addRoute($method, $path, $action);
                }
                break;
            case 'middlewares':
                $this->pipe($registrants);
                break;
            default:
                break;
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
