<?php

namespace App\Router;

use App\Interfaces\HttpMethods;
use App\Interfaces\RequestInterface;

class Route
{
    public function __construct(private $url, private readonly HttpMethods $method, private $callback)
    {
    }

    public function matches(RequestInterface $request): bool
    {
        return $this->url === $request->getUri() && $this->method === $request->getMethod();
    }

    public function getCallback(): callable
    {
        return call_user_func($this->callback);
    }
}