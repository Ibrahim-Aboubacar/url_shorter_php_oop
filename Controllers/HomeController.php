<?php

namespace Controllers;

use Source\Renderer;

class HomeController
{
    public function index(): Renderer
    {
        $vars = [
            'pageName' => 'home',
        ];
        return Renderer::make('home/index', $vars);
    }
}
