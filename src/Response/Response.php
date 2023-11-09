<?php

namespace App\Response;

use App\Interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    public function __construct(protected readonly string $response, protected $statusCode, protected $headers = [])
    {
    }

    public function send(): string
    {

        return $this->response;
    }
}