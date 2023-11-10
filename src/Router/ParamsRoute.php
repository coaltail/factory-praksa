<?php

namespace App\Router;

use App\Interfaces\HttpMethods;
use App\Router\Route;

class ParamsRoute extends Route
{
    public function __construct(protected $url, protected HttpMethods $method, protected $callback)
    {
        parent::__construct($url, $method, $callback);
    }
    public function getUrlWithoutParameters(): string
    {
        return preg_replace('/\/\{\w+\}/', '', $this->url);
    }


}