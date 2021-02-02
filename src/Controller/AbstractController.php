<?php

namespace App\Controller;

use App\Service\TwigService;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    protected function render(string $template, array $params = []): Response
    {
        $content = TwigService::getInstance()->render($template, $params);

        return new Response($content);
    }
}
