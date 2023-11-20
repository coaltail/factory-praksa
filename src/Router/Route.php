<?php

namespace App\Router;

use App\Interfaces\HttpMethods;
use App\Interfaces\RequestInterface;
use App\Response\Response;
use App\Request\Request;
use Exception;

class Route
{
    private readonly string $url;
    private readonly HttpMethods $httpMethod;
    private $callback;

    /**
     * @throws Exception
     */
    public function __construct(string $url, HttpMethods $httpMethod, $callback)
    {
        $this->validateUrlSegments($url);

        $this->url = $url;
        $this->httpMethod = $httpMethod;
        $this->callback = $callback;
    }

    /**
     * @throws Exception
     */
    public static function get(string $url, $callback): void
    {
        Router::registerRoute(new static($url, HttpMethods::GET, $callback));
    }

    /**
     * @throws Exception
     */
    public static function post(string $url, $callback): void
    {
        Router::registerRoute(new static($url, HttpMethods::POST, $callback));
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getHttpMethod(): HttpMethods
    {
        return $this->httpMethod;
    }

    public function invokeCallback(Request $request, array $params = [])
    {
        return call_user_func($this->callback, $request, ...array_values($params));
    }

    public function fetchParams($url): array
    {
        $params = [];
        $requestUrlSegments = explode("/", $url);
        $routeUrlSegments = explode("/", $this->getUrl());

        foreach ($routeUrlSegments as $index => $routeUrlSegment) {
            $this->processRouteSegment($routeUrlSegment, $requestUrlSegments, $index, $params);
        }

        return $params;
    }

    /**
     * @throws Exception
     */
    private function validateUrlSegments(string $url): void
    {
        $urlSegments = explode("/", $url);

        foreach ($urlSegments as $urlSegment) {
            if (strlen($urlSegment) === 2 && $urlSegment[0] === "{" && $urlSegment[strlen($urlSegment) - 1] === "}") {
                throw new Exception(':(');
            }
        }
    }

    private function processRouteSegment(string $routeUrlSegment, array $requestUrlSegments, int $index, array &$params): void
    {
        if (strlen($routeUrlSegment) >= 2 && $routeUrlSegment[0] === "{" &&
            $routeUrlSegment[strlen($routeUrlSegment) - 1] === "}"
        ) {
            if (isset($requestUrlSegments[$index])) {
                $params[substr($routeUrlSegment, 1, -1)] = $requestUrlSegments[$index];
            }
        }
    }
}
