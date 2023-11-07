<?php

namespace App\Request;

use App\Interfaces\HttpMethods;
use App\Interfaces\RequestInterface;

class Request implements RequestInterface
{


    public function __construct(private readonly string $url, private readonly HttpMethods $method) {

    }

    public function getUrl(): string {
        return $this->url;
    }

    public function getMethod() : HttpMethods {
        return $this->method;
    }




}