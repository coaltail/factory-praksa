<?php

use \App\Router\Router;
use \App\Interfaces\HttpMethods;
use \App\Router\Route;
use \App\Response\Response;
use App\Response\HtmlResponse;
use App\Controllers\IndexController;
use App\Router\ParamsRoute;
$indexController = new IndexController();

Router::registerRoute(new Route('/', HttpMethods::GET, function() use ($indexController){
    return $indexController->indexAction('Index route');
}));

Router::registerRoute(new ParamsRoute('/{id}', HttpMethods::GET, function (array $params) use ($indexController){
    return $indexController->indexAction('Parameterized route: ' . $params['']);
}));

Router::registerRoute(new Route('/g/asd', HttpMethods::GET, function () use ($indexController) {
    return $indexController->indexJsonAction('Json route', 200);
}));

Router::registerRoute(new Route('/new/route', HttpMethods::GET, function () use ($indexController) {
    return $indexController->indexHtmlAction(['HTML Value' => 'HTML Key']);
}));