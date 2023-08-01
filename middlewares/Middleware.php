<?php

namespace Middlewares;

use Exception;
use Router\Router;

class Middleware
{
    // NOTRE LIST DE MIDDLEWARE
    const MAP = [
        'auth' => AuthMiddleware::class,
        'guest' => GuestMiddleware::class
    ];

    public static function resolve($key, Router $router)
    {
        if (is_null($key)) return;

        $middleware = static::MAP[$key] ?? false;

        if (!$middleware) {
            throw new Exception("No mathing middleware for the key '{$key}'.");
        }

        if (class_exists($middleware) && method_exists($middleware, 'handle')) {

            $class = new $middleware();

            return call_user_func_array([$class, 'handle'], [$router]);
        } else {
            throw new Exception("Class or method not found for '{$middleware}' @ 'handle'.");
        }
    }
}
