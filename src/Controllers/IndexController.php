<?php

namespace App\Controllers;

use App\Interfaces\ResponseInterface;
use App\Response\HtmlResponse;
use App\Response\JsonResponse;
use App\Response\Response;
use App\Request\Request;
class IndexController
{

    public function __construct()
    {

    }

    public static function indexAction(Request $request): Response
    {
        return new Response('This is the new resposne bla bla');
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