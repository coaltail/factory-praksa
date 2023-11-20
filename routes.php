<?php

use \App\Router\Router;
use \App\Interfaces\HttpMethods;
use \App\Router\Route;
use \App\Response\Response;
use App\Response\HtmlResponse;
use App\Controllers\IndexController;
use App\Controllers\UserController;

Route::get('/', [IndexController::class, 'indexAction']);
Route::get('/users/create', [UserController::class, 'createUser']);