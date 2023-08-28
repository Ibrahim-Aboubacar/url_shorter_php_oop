<?php

namespace Middlewares;

use Source\App;
use Router\Router;

class GuestMiddleware
{
    public function handle()
    {
        $router = App::getRouter();
        if (isset($_SESSION['auth']['id'])) {
            // Renvoyer le code de réponse 401 Unauthorized
            http_response_code(401);
            header('location: ' . $router->url('home'));
            die;
        }
    }
}
