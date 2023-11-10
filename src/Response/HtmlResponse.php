<?php

namespace App\Response;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class HtmlResponse extends Response
{
    private FilesystemLoader $loader;
    private Environment $twig;

    public function __construct(protected array $content)
    {
        parent::__construct($content);
        $this->loader = new FilesystemLoader('templates/');
        $this->twig = new Environment($this->loader, ['debug' => true]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function send(): string
    {
        return $this->twig->render('index.twig', $this->content);
    }

}