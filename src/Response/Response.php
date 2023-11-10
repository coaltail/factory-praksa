<?php

namespace App\Response;

use App\Interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    public function __construct(protected $response)
    {
    }

    public function send(): string
    {

        return $this->response;
    }
}