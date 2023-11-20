<?php

use \App\Router\Router;
use \App\Interfaces\HttpMethods;
use \App\Router\Route;
use \App\Response\Response;
use App\Response\HtmlResponse;
use App\Controllers\IndexController;
use App\Controllers\UserController;
use App\Request\Request;
use App\Response\JsonResponse;

Route::get('/', [IndexController::class, 'indexAction']);
Route::get('/users/create', [UserController::class, 'createUser']);
Route::post('/asd', function () {
    return new JsonResponse('Sending json', 200);
});
Route::get('/{id}', function (Request $request, $id) {
    return new Response('Id is: ' . $id);
});