<?php

namespace App\Response;

use App\Interfaces\ResponseInterface;

class JsonResponse extends Response
{
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