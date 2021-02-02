<?php
declare(strict_types=1);

namespace App\Service;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigService extends Environment
{
    private static ?Environment $instance = null;

    public static function getInstance(): TwigService
    {
        if (self::$instance === null) {
            $loader = new FilesystemLoader(dirname('../../') . '/templates');
            self::$instance = new self($loader);
        }

        return self::$instance;
    }
}
