<?php

namespace App\ErrorFeedback;

use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorRendererInterface;
use Slim\Views\Twig;
use Throwable;

class PageNotFoundRenderer implements ErrorRendererInterface
{
    public function __construct(
        private readonly Twig $twig
    ) {
    }

    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        if ($exception instanceof HttpNotFoundException) {
            return $this->twig->fetch('feedback/404.twig', [
                'title' => 'Page not found',
            ]);
        }

        return $exception->getMessage();
    }
}
