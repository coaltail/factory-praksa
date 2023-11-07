<?php

namespace App\Response;

use App\Interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    public function __construct(private readonly string $response, private $statusCode, private $headers = []) {}

    public function send(): string
    {

        $response = [
            'data' => $this->response,
            'status' => $this->statusCode,
            'headers' => $this->headers,
        ];


        return json_encode($response);
    }
}