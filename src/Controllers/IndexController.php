<?php

namespace App\Controllers;

use App\Interfaces\ResponseInterface;
use App\Response\HtmlResponse;
use App\Response\JsonResponse;
use App\Response\Response;

class IndexController
{

    public function __construct()
    {

    }

    public function indexAction(string $content): Response
    {
        return new Response($content);
    }

    public function indexJsonAction(string $content, int $statusCode): JsonResponse
    {
        return new JsonResponse($content, $statusCode);
    }

    public function indexHtmlAction(array $content): HtmlResponse
    {
        return new HtmlResponse($content);
    }

}