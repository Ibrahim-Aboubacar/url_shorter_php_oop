<?php

namespace Controllers;

use Source\Renderer;

class HomeController
{
    public function index(): Renderer
    {
        $vars = [
            'pageName' => '',
        ];
        return Renderer::make('home/index', $vars);
    }
}
