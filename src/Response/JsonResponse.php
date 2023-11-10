<?php

namespace App\Response;

use App\Interfaces\ResponseInterface;

class JsonResponse extends Response
{

    public function __construct(string $response, protected int $statusCode, protected array $headers = [])
    {
        parent::__construct($response);
    }

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