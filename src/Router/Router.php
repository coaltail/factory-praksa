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
        $filteredRoutes = array_filter(self::$routes, function (Route $route) use ($request) {
            return $route instanceof ParamsRoute
                ? self::matchParamsRoute($route, $request)
                : $route->matches($request);
        });

        $filteredRoute = reset($filteredRoutes);

        if ($filteredRoute) {
            return $filteredRoute instanceof ParamsRoute
                ? self::handleParamsRoute($filteredRoute, $request)
                : $filteredRoute->getCallback($request->getRequestParams());
        }

        self::handleNotFound();
    }

    private static function matchParamsRoute(ParamsRoute $route, Request $request): bool
    {
        $urlWithoutParams = $route->getUrlWithoutParameters();
        $urlWithoutParamsPlaceholder = str_replace("/{", "/", $urlWithoutParams);
        $position = strrpos($request->getUrl(), "/");

        return $urlWithoutParamsPlaceholder === substr($request->getUrl(), 0, $position) && $route->getMethod() === $request->getMethod();
    }


    private static function handleParamsRoute(ParamsRoute $route, Request $request)
    {
        $urlWithoutParams = $route->getUrlWithoutParameters();
        $paramName = substr(strrchr($request->getUrl(), "/"), 1);
        $params = $request->getRequestParams() + [$urlWithoutParams => $paramName];

        return $route->getCallback($params);
    }

    #[NoReturn] private static function handleNotFound()
    {
        echo "This site does not exist";
        exit;
    }
}
