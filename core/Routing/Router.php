<?php

namespace Joogli\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{
    protected static $routable = [
        Request::METHOD_HEAD,
        Request::METHOD_GET,
        Request::METHOD_POST,
        Request::METHOD_PUT,
        Request::METHOD_PATCH,
        Request::METHOD_DELETE,
        Request::METHOD_PURGE,
        Request::METHOD_OPTIONS,
        Request::METHOD_TRACE,
        Request::METHOD_CONNECT,
    ];

    protected static $routes = [];

    protected static $classNamespace = 'App\\Controllers\\';

    public static function loadRoutes()
    {
        require_once base_path('routes/web.php');
    }

    public static function __callStatic(string $name, array $arguments): ?bool
    {
        $name = strtoupper($name);
        if (in_array($name, static::$routable)) {
            static::bindRoute($name, $arguments);
            return true;
        }

        throw new \InvalidArgumentException('non-http adjective used');
    }

    protected static function bindRoute($method, $args)
    {
        if (count($args) < 2) {
            throw new \InvalidArgumentException('incomplete route arguments');
        }

        if (!is_string($args[0])) {
            throw new \InvalidArgumentException('route not passed');
        }

        $action = is_string($args[1])
            ? static::resolveRouteString($args[1])
            : $args[1];

        static::$routes[$method][$args[0]] = new Route($method, $args[0], $action);
    }

    protected static function resolveRouteString(string $string): array
    {
        if (strpos($string, '@') === false) {
            $method = 'action';
        } else {
            $elements = explode('@', $string);
            $string = $elements[0];
            $method = $elements[1];
        }

        if (!class_exists($string)) {
            $string = static::$classNamespace . $string;
            if (!class_exists($string)) {
                throw new \InvalidArgumentException('controller class not found');
            }
        }

        if (!method_exists($string, $method)) {
            throw new \InvalidArgumentException('controller class does not implement method');
        }

        return [
            'class'  => $string,
            'method' => $method
        ];
    }

    public static function action(Request $request)
    {
        if (!isset(static::$routes[$request->getMethod()][$request->getPathInfo()])) {
            return new Response(
                '404',
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'text/html']
            );
        }

        return static::$routes[$request->getMethod()][$request->getPathInfo()]->execute($request);
    }
}
