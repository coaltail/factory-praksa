<?php

namespace App\Router;

use App\Interfaces\HttpMethods;
use App\Interfaces\RequestInterface;
use App\Request\Request;
use App\Response\Response;
use JetBrains\PhpStorm\NoReturn;
use Exception;
class Router
{
    private static array $routes = [];

    /**
     * @throws Exception
     */
    public static function registerRoute(Route $route): void
    {
        // Check if a similar route already exists
        foreach (self::$routes as $existingRoute) {
            if ($existingRoute->getUrl() === $route->getUrl() && $existingRoute->getHttpMethod() === $route->getHttpMethod()) {
                throw new Exception("Route for '{$route->getUrl()}' with method '{$route->getHttpMethod()}' already exists.");
            }
        }

        // If not, add the route
        self::$routes[] = $route;
    }

    public static function resolve(Request $request)
    {
        $requestUrlSegments = explode("/", $request->getUrl());

        $filteredRoute = self::findMatchingRoute($requestUrlSegments, $request->getMethod());

        if ($filteredRoute !== null) {
            $urlParams = $filteredRoute->fetchParams($request->getUrl());
            return $filteredRoute->invokeCallback($request, $urlParams);
        }

        self::handleNotFound();
    }

    private static function findMatchingRoute(array $requestUrlSegments, HttpMethods $httpMethod): ?Route
    {
        foreach (self::$routes as $route) {
            $routeUrlSegments = explode("/", $route->getUrl());

            if (count($requestUrlSegments) === count($routeUrlSegments) &&
                $route->getHttpMethod() === $httpMethod &&
                self::areSegmentsMatching($requestUrlSegments, $routeUrlSegments)
            ) {
                return $route;
            }
        }

        return null;
    }

    private static function areSegmentsMatching(array $requestUrlSegments, array $routeUrlSegments): bool
    {
        foreach ($routeUrlSegments as $index => $routeUrlSegment) {
            if (!(strlen($routeUrlSegment) >= 2 && $routeUrlSegment[0] === "{" &&
                $routeUrlSegment[strlen($routeUrlSegment) - 1] === "}")) {
                if ($routeUrlSegment !== $requestUrlSegments[$index]) {
                    return false;
                }
            }
        }

        return true;
    }
    #[NoReturn] private static function handleNotFound(): void
    {
        echo "This site does not exist";
        exit;
    }
}
