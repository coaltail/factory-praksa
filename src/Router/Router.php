<?php

namespace App\Router;
use App\Interfaces\RequestInterface;
use App\Response\Response;

class Router
{
    private static ?Router $instance = null;
    private array $routes = [];
    private function __construct() {}

    // Create router singleton for simplicity
    public static function getInstance(): Router {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function registerRoute(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function resolve(RequestInterface $request)
    {
        $matchingRoute = $this->findMatchingRoute($request);

        if ($matchingRoute !== null) {
            return $matchingRoute->getCallback();
        }

        return new Response("Not Found", 404);
    }

    private function findMatchingRoute(RequestInterface $request): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->matches($request)) {
                return $route;
            }
        }

        return null;
    }
}
