<?php

namespace App\Request;

use App\Interfaces\HttpMethods;
use App\Interfaces\RequestInterface;

class Request implements RequestInterface
{
    private array $requestParams;

    public function __construct()
    {
        $this->requestParams = [
            'GET' => $_GET,
            'POST' => $_POST
        ];
    }


    public function getUrl(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function getMethod(): ?HttpMethods
    {
        return match ($_SERVER['REQUEST_METHOD']) {
            'POST' => HttpMethods::POST,
            'GET' => HttpMethods::GET,
            default => null,
        };
    }

    public function getRequestParams(): array
    {
        return $this->requestParams;
    }

}