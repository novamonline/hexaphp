<?php

namespace HexaPHP\Libs\Application;

use HexaPHP\Libs\HttpClient\IRequest;
use HexaPHP\Libs\HttpClient\IResponse;
use HexaPHP\Libs\HttpClient\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Controller
{
    protected IRequest $request;
    protected IResponse $response;
    
    public function __construct(IRequest $request, IResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    protected function json(array $data, int $status = 200): IResponse
    {
        $body = json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $this->response->getBody()->write($body);
        return $this->response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
    
    protected function html(string $content, int $status = 200): IResponse
    {
        $this->response->getBody()->write($content);
        return $this->response->withHeader('Content-Type', 'text/html')->withStatus($status);
    }
    
    protected function redirect(string $url, int $status = 302): IResponse
    {
        return $this->response->withHeader('Location', $url)->withStatus($status);
    }
}
