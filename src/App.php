<?php

namespace Source;

use PDO;
use Router\Router;
use Source\Constant;

class App
{
    public static $router;
    public static $pdo;
    public static $app_name;

    public static function setRouter(Router $router): void
    {
        static::$router = $router;
    }

    public static function getRouter(): Router
    {
        return static::$router;
    }

    public static function getPDO(): PDO
    {
        if (!static::$pdo) {
            static::$pdo = new PDO(
                'mysql:dbname=' . Constant::DB_NAME . ';host=' . Constant::DB_HOST,
                Constant::DB_USERNAME,
                Constant::DB_PASSWORD,
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
        }
        return static::$pdo;
    }

    public static function getAppName()
    {
        if (!static::$app_name) {
            static::$app_name = Constant::APP_NAME;
        }
        return static::$app_name;
    }
}
