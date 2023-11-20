<?php

namespace App\Router;

use App\Interfaces\RequestInterface;
use App\Request\Request;
use App\Response\Response;
use JetBrains\PhpStorm\NoReturn;

class Router
{
    private static array $routes = [];

    public static function registerRoute(Route $route): void
    {
        self::$routes[] = $route;
    }

    public static function resolve(Request $request)
    {
        $requestUrlSegments = explode("/", $request->getUrl());

        $filteredRoute = self::findMatchingRoute($requestUrlSegments);

        if ($filteredRoute !== false) {
            $urlParams = $filteredRoute->fetchParams($request->getUrl());
            return $filteredRoute->invokeCallback($request, $urlParams);
        }

        self::handleNotFound();
    }

    private static function findMatchingRoute(array $requestUrlSegments): ?Route
    {
        foreach (self::$routes as $route) {
            $routeUrlSegments = explode("/", $route->getUrl());

            if (count($requestUrlSegments) !== count($routeUrlSegments)) {
                continue;
            }

            if (self::areSegmentsMatching($requestUrlSegments, $routeUrlSegments)) {
                return $route;
            }
        }

        return null;
    }

    private static function areSegmentsMatching(array $requestUrlSegments, array $routeUrlSegments): bool
    {
        foreach ($routeUrlSegments as $index => $routeUrlSegment) {
            if (strlen($routeUrlSegment) >= 2 && $routeUrlSegment[0] === "{" &&
                $routeUrlSegment[strlen($routeUrlSegment) - 1] === "}"
            ) {
                continue;
            }

            if ($routeUrlSegment !== $requestUrlSegments[$index]) {
                return false;
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
