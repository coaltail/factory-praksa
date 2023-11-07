<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/vendor/autoload.php";
require_once 'routes.php';
$router = \App\Router\Router::getInstance();

$req = new \App\Request\Request("/", \App\Interfaces\HttpMethods::GET);

$response = $router->Resolve($req);
echo $response->send();