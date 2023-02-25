<?php

declare(strict_types=1);

namespace App\Controller;

use Predis\Client as RedisClient;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class HomeController
{
    public function __construct(
        private Twig $twig,
        private RedisClient $redis,
    ) {}

    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'home.twig', [
            'greetingText' => false ? 'Email sent' : 'Email not sent',
            'visitTime' => $this->redis->incr('visits'),
        ]);
    }
}
