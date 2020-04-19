<?php

namespace Joogli\Utils;

use Twig\Loader\FilesystemLoader;

class View
{
    protected static $twig = null;

    public static function init(): void
    {
        static::$twig = new \Twig\Environment(new FilesystemLoader(base_path('resources/templates')), [
            'cache' => base_path('resources/cache'),
        ]);
    }

    public static function make(string $template, array $args = null): string
    {
        return static::$twig->render($template, $args);
    }
}