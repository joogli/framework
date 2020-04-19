<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Joogli\Routing\Router;
use Joogli\Http\Kernel;

$request = Request::createFromGlobals();

Kernel::emit(Router::action($request));
