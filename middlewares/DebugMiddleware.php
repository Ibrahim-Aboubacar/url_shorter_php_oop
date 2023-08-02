<?php

namespace Middlewares;

use Source\Constant;
use Source\Renderer;

class DebugMiddleware
{
    public function handle()
    {
        if (!Constant::DEBUG_MODE) {
            // Renvoyer le code de réponse 401 Unauthorized
            // http_response_code(404);
            die(Renderer::error404("Cette page n'est pas autorisé"));
            return;
        }
    }
}
