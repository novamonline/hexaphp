<?php

namespace HexaPHP\Libs\HttpClient;

use InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements ServerRequestInterface
{
    private $method;
    private $uri;
    private $headers;
    private $body;
    private $protocolVersion;
    private $serverParams;
    private $cookieParams;
    private $queryParams;
    private $parsedBody;
    private $uploadedFiles;
    private $attributes;

    public function __construct(
        string $method,
        UriInterface $uri,
        array $headers = [],
        StreamInterface $body = null,
        string $protocolVersion = '1.1',
        array $serverParams = [],
        array $cookieParams = [],
        array $queryParams = [],
        $parsedBody = null,
        array $uploadedFiles = [],
        array $attributes = []
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body ?: new Stream(fopen('php://temp', 'r+'));
        $this->protocolVersion = $protocolVersion;
        $this->serverParams = $serverParams;
        $this->cookieParams = $cookieParams;
        $this->queryParams = $queryParams;
        $this->parsedBody = $parsedBody;
        $this->uploadedFiles = $uploadedFiles;
        $this->attributes = $attributes;
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookies): self
    {
        $new = clone $this;
        $new->cookieParams = $cookies;
        return $new;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): self
    {
        $new = clone $this;
        $new->queryParams = $query;
        return $new;
    }

    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): self
    {
        $new = clone $this;
        $new->parsedBody = $data;
        return $new;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): self
    {
        $new = clone $this;
        $new->uploadedFiles = $uploadedFiles;
        return $new;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value): self
    {
        $new = clone $this;
        $new->attributes[$name] = $value;
        return $new;
    }

    public function withoutAttribute($name): self
    {
        $new = clone $this;
        unset($new->attributes[$name]);
        return $new;
    }

    public function getRequestTarget(): string
    {
        $target = $this->uri->getPath();
        if ($this->uri->getQuery()) {
            $target .= '?' . $this->uri->getQuery();
        }
        return $target ?: '/';
    }

    public function withRequestTarget($requestTarget): self
    {
        if (strpos($requestTarget, ' ') !== false) {
            throw new InvalidArgumentException('Invalid request target provided; must not contain whitespace');
        }
        $new = clone $this;
        $new->uri = $new->uri->withPath($requestTarget);
        return $new;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method): self
    {
        $new = clone $this;
        $new->method = $method;
        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): self
    {
        $new = clone $this;
        $new->uri = $uri;
        if (!$preserveHost) {
            $new->updateHostHeaderFromUri();
        }
        return $new;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): self
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader($name): array
    {
        $name = strtolower($name);
        if (!$this->hasHeader($name)) {
            return [];
        }
        return $this->headers[$name];
    }

    public function getHeaderLine($name): string
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader($name, $value): self
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $new = clone $this;
        $new->headers[strtolower($name)] = $value;
        return $new;
    }

    public function withAddedHeader($name, $value): self
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $new = clone $this;
        $name = strtolower($name);
        if ($new->hasHeader($name)) {
            $new->headers[$name] = array_merge($new->headers[$name], $value);
        } else {
            $new->headers[$name] = $value;
        }
        return $new;
    }

    public function withoutHeader($name): self
    {
        $new = clone $this;
        $name = strtolower($name);
        unset($new->headers[$name]);
        return $new;
    }

    // The following methods are inherited from the MessageInterface

    public function getBody(): StreamInterface
    {
        return $this->body;
    }
}

