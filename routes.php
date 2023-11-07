<?php


$router = \App\Router\Router::getInstance();

$router->RegisterRoute(new \App\Router\Route("/", \App\Interfaces\HttpMethods::GET, function () {
    return new App\Response\Response("Test", 200, headers: ["Content-Type" => "application/json"]);
}));