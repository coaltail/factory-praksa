<?php

namespace App\Router;

use App\Interfaces\RequestInterface;
use App\Response\Response;

class Router
{

    private static array $routes = [];

    public static function registerRoute(Route $route): void
    {
        self::$routes[] = $route;
    }

    public static function resolve(RequestInterface $request): callable|Response
    {
        $matchingRoute = self::findMatchingRoute($request);

        if ($matchingRoute !== null) {
            return $matchingRoute->getCallback();
        }

        return new Response('Not Found', 404);
    }

    private static function findMatchingRoute(RequestInterface $request): ?Route
    {
        foreach (self::$routes as $route) {
            if ($route->matches($request)) {
                return $route;
            }
        }

        return null;
    }
}
