<?php

namespace App;

use Slim\Views\TwigExtension;
use Twig\TwigFunction;

class TwigCustomExtensions extends TwigExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('activeClass', [$this, 'activeClass'], ['needs_context' => true])
        ];
    }

    public function activeClass(array $context, $page)
    {
        if (isset($context['current_page']) && $context['current_page'] === $page) {
            return 'active';
        }
    }
}
