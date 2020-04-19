<?php

namespace Joogli\Routing;

use Symfony\Component\HttpFoundation\Request;

class Route
{
    protected $method;
    protected $route;
    protected $action;
    
    public function __construct($method, $route, $action)
    {
        $this->method = $method;
        $this->route = $route;
        $this->action = $action;
    }
    
    public function execute(Request $request)
    {
        if (is_callable($this->action)) {
            return ($this->action)($request);
        }

        if (is_array($this->action)) {
            return (new $this->action['class']())
                ->{$this->action['method']}($request);
        }

        throw new \Error('something went very badly wrong');
    }
}