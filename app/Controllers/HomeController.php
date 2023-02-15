<?php

declare(strict_types=1);

namespace App\Controllers;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class HomeController
{
    public function index(Request $request, Response $response): Response
    {
        return Twig::fromRequest($request)->render($response, 'home.twig', [
            'greetingText' => 'This is hardcoded now, but returned from Controller'
        ]);
    }
}