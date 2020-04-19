<?php

if (!function_exists('base_path')) {
    function base_path(string $path = null): string
    {
        return dirname(__DIR__) . (substr($path, 0, 1) !== '/' && $path ? '/' : '') . $path;
    }
}

if (!function_exists('view')) {
    function view(string $template, array $args = null): string
    {
        return \Joogli\Utils\View::make($template, $args);
    }
}