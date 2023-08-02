<?php

namespace Router;

use Exceptions\RouterException;
use Middlewares\Middleware;

class Route
{
    private $path;
    private $matches = [];
    private $params = [];
    private $middleware = null;

    public function __construct($path, private $callable)
    {
        $this->path = trim($path, '/');
    }

    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        // '([^/]+)'
        // CASE SENSITIVE SANS LE i ET CASE INSENSITIVE AVEC LE i
        $regex = "#^$path$#i";

        if (!preg_match($regex, $url, $matches)) {
            return false;
        }

        array_shift($matches);

        $this->matches = $matches;
        return true;
    }

    private function paramMatch($match)
    {
        if (isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }

        return '([^/]+)';
    }

    public function call(Router $router)
    {
        if (is_callable($this->callable)) {

            // APPEL AU MIDDLEWARE 
            Middleware::resolve($this->getMiddleware(), $router);

            $action = $this->callable;
            return $action();
        }

        if (is_array($this->callable)) {
            [$className, $method] = $this->callable;

            if (class_exists($className) && method_exists($className, $method)) {
                $class = new $className();

                // APPEL AU MIDDLEWARE 
                Middleware::resolve($this->getMiddleware(), $router);

                return call_user_func_array([$class, $method], [...$this->matches, $router]);
            }
        }

        throw new RouterException("Couldn't execute any callable or controller!");
    }

    public function with($param, $regex)
    {
        $this->params[$param] = str_replace('(', '(?:', $regex);

        return $this;
    }

    public function middleware($key)
    {
        $this->setMiddleware($key);
        // Middleware::resolve($key, new Router($_GET['url']));
        return $this;
    }

    public function getUrl($params)
    {
        $path = $this->path;

        foreach ($params as $k => $v) {
            $path = str_replace(":$k", $v, $path);
        }

        return $path;
    }

    /**
     * Get the value of middleware
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * Set the value of middleware
     *
     * @return  self
     */
    public function setMiddleware($middleware)
    {
        $this->middleware = $middleware;

        return $this;
    }
}