<?php

namespace Middlewares;

use Router\Router;

class AuthMiddleware
{
    public function handle(Router $router)
    {
        if (!isset($_SESSION['auth']['id'])) {
            // Renvoyer le code de réponse 401 Unauthorized
            http_response_code(401);
            header('location: ' . $router->url('user.login'));
            die;
        }
    }
}
