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
    private $namedRoutes = [];

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

    public function put($path, $callable, $name = null)
    {
        return $this->register($path, $callable, 'PUT', $name);
    }

    public function patch($path, $callable, $name = null)
    {
        return $this->register($path, $callable, 'PATCH', $name);
    }

    public function delete($path, $callable, $name = null)
    {
        return $this->register($path, $callable, 'DELETE', $name);
    }

    public function routeList()
    {
        return $this->register("/route-list", [RouteController::class, 'index'], 'GET', '__route.list')->withMiddleware('debug');
    }

    public function register($path, $callable, $method, $name)
    {
        if (Constant::DEBUG_MODE) {
            if (str_contains(Constant::DOMAIN, 'localhost') || str_contains(Constant::DOMAIN, '127.0.0.1')) $path = trim(Constant::DOMAIN . 'public/', '/') . $path;
        }

        $route = new Route($path, $callable);

        $this->routes[$method][] = $route;

        if ($name) {
            $this->namedRoutes[$name] = $route;
        }


        return $route;
    }

    public function run()
    {
        try {
            $_SESSION['router'] = $this;

            $requestMethod = $this->getRequestMethod();

            /**
             * @var Route $route an instance of Route
             */
            foreach ($this->routes[$requestMethod] as $route) {

                if ($route->match($this->url)) {
                    die($route->execute($this));
                }
            }

            // throw new RouterException('No matching routes');
            echo Renderer::error404('Page not found');
            return;
        } catch (\Exception $e) {

            throw new RouterException($e->getMessage(), true);

            die(Renderer::error500());
        }
    }

    protected function getRequestMethod()
    {
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            throw new RouterException('REQUEST METHOD does not exist');
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                return $_POST[Constant::REQUEST_METHOD_NAME] ?? $_SERVER['REQUEST_METHOD'];
            } else {
                return $_SERVER['REQUEST_METHOD'];
            }
        }
    }

    public function url($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            return Constant::DOMAIN;
            // throw new RouterException('No route matches this name');
        }

        $url = $this->namedRoutes[$name]->getUrl($params);

        if (Constant::DEBUG_MODE) {
            if (str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) return $url;
        }

        return Constant::DOMAIN . $url;
    }

    /**
     * Get the value of routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Get the value of namedRoutes
     */
    public function getNamedRoutes()
    {
        return $this->namedRoutes;
    }
}
