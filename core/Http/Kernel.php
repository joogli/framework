<?php

namespace Joogli\Http;

use Symfony\Component\HttpFoundation\Response;

class Kernel
{
    public static function emit($content): void
    {
        static::createResponse($content)->send();
    }

    public static function createResponse($content): Response
    {
        if ($content instanceof Response) {
            return $content;
        }

        if (!is_string($content)) {
            ob_start();
            $content = dump($content);
            ob_end_clean();
        }

        return new Response(
            $content,
            Response::HTTP_NOT_FOUND,
            ['content-type' => 'text/html']
        );
    }
}