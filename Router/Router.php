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
}
