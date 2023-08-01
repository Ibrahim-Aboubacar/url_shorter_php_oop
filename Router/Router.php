<?php

namespace Router;

use Router\Route;
use Source\Constant;
use Source\Renderer;
use Exceptions\RouterException;

class Router
{
    private $url;
    private $routes = [];
    public $namedRoutes = [];

    public function __construct($url)
    {
        if ($url === '') {
            $this->url = '/';
        } else {
            $this->url = $url;
        }
    }

    public function get($path, $callable, $name = null)
    {
        return $this->register($path, $callable, 'GET', $name);
    }

    public function post($path, $callable, $name = null)
    {
        return $this->register($path, $callable, 'POST', $name);
    }

    public function register($path, $callable, $method, $name)
    {
        $route = new Route($path, $callable);

        $this->routes[$method][] = $route;

        if ($name) {
            $this->namedRoutes[$name] = $route;
        }

        return $route;
    }

    public function run()
    {
        $_SESSION['router'] = $this;

        if (!isset($_SERVER['REQUEST_METHOD'])) {
            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {

            if ($route->match($this->url)) {
                echo $route->call($this);
                return;
            }
        }

        // throw new RouterException('No matching routes');
        echo Renderer::error404('Page not found');
        return;
    }

    public function url($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            return Constant::DOMAIN;
            // throw new RouterException('No route matches this name');
        }

        return Constant::DOMAIN . $this->namedRoutes[$name]->getUrl($params);
    }





















































    // private array $routes = [];

    // public function register(string $path, callable|array $action, string $verb, string $name): void
    // {
    //     $this->routes[$verb][$path] = [$action, $name];
    // }

    // public function get(string $path, callable|array $action, $name = ''): void
    // {
    //     $this->register($path, $action, 'GET', $name);
    // }

    // public function post(string $path, callable|array $action, $name = ''): void
    // {
    //     $this->register($path, $action, 'POST', $name);
    // }

    // public function routes(): array
    // {
    //     return $this->routes;
    // }

    // public function resolve(string $requestUri, string $requestMethod, Router $router): mixed
    // {
    //     // $path = explode('?', $requestUri)[0];
    //     $path = $this->url;
    //     $action = $this->routes[$requestMethod][$path][0] ?? null;
    //     // $name = $this->routes[$requestMethod][$path][1] ?? null;

    //     if (is_callable($action)) {
    //         return $action($router);
    //     }

    //     if (is_array($action)) {
    //         [$className, $method] = $action;

    //         if (class_exists($className) && method_exists($className, $method)) {
    //             $class = new $className();
    //             return call_user_func_array([$class, $method], [$router]);
    //         }
    //     }

    //     throw new RouteNotFoundException();
    // }

    // public function path($name)
    // {
    //     foreach ($this->routes as $methodRoutes) {
    //         foreach ($methodRoutes as $path => $controllerAction) {
    //             // Vérifier si le nom correspond à l'action du contrôleur
    //             if (strpos($controllerAction[1], $name) !== false) {
    //                 return $path;
    //             }
    //         }
    //     }

    //     // Si aucune correspondance n'est trouvée, retourner null ou une valeur par défaut selon vos besoins
    //     return '#';
    // }
}
