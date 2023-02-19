<?php

declare(strict_types=1);

namespace App\Controller;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class HomeController
{
    public function __construct(
        private Twig $twig,
    ) {}

    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'home.twig', [
            'greetingText' => 'This is hardcoded now, but returned from Controller'
        ]);
    }
}
