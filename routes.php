<?php

use \App\Router\Router;
use \App\Interfaces\HttpMethods;
use \App\Router\Route;
use \App\Response\Response;


Router::registerRoute(new Route('/', HttpMethods::GET, function () {
    return new Response('Test', 200, headers: ['Content-Type" => "application/json']);
}));