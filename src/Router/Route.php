<?php

namespace App\Router;

use App\Interfaces\HttpMethods;
use App\Interfaces\RequestInterface;
use App\Response\Response;

class Route
{
    public function __construct(private $url, private readonly HttpMethods $method, private $callback)
    {
    }

    public function matches(RequestInterface $request): bool
    {
        return $this->url === $request->getUrl() && $this->method === $request->getMethod();
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getMethod(): ?HttpMethods
    {
        return match ($_SERVER['REQUEST_METHOD']) {
            'POST' => HttpMethods::POST,
            'GET' => HttpMethods::GET,
            default => null,
        };
    }

    public function getCallback(array $params = []): callable|Response
    {
        return call_user_func($this->callback, $params);
    }
}