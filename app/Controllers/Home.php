<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;

class Home
{
    public function view(Request $request)
    {
        return view('home.twig', ['template' => microtime(true)]);
    }
}