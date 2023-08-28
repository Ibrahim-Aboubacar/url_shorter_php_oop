<?php

namespace Middlewares;

use Exceptions\RouterException;
use Router\Router;

class Middleware
{   // NOTRE LIST DE MIDDLEWARE
    const MAP = [
        'auth' => AuthMiddleware::class,
        'guest' => GuestMiddleware::class,
        'debug' => DebugMiddleware::class,
    ];

    public static function resolve($key)
    {
        if (is_null($key)) return;

        if (!self::isValid($key)) throw new RouterException("No mathing middleware for the key '{$key}'.", true);
        $classOfMiddleware = static::MAP[$key];

        if (class_exists($classOfMiddleware)) {

            if (method_exists($classOfMiddleware, 'handle')) return call_user_func_array([(new $classOfMiddleware()), 'handle'], []);

            throw new RouterException("Method 'handle' not found in {$classOfMiddleware}.", true);
        } else {
            throw new RouterException("Class '{$classOfMiddleware}' not found.", true);
        }
    }

    public static function isValid($middlewareKey): bool
    {
        return array_key_exists($middlewareKey, self::MAP);
    }
}
