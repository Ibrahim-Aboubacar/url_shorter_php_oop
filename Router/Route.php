<?php

namespace Router;

use Exceptions\RouterException;
use Middlewares\Middleware;
use Source\Constant;
use Source\Dump;

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

        $thisPath = $this->path;

        if (Constant::DEBUG_MODE) {
            // SUPPORT FOR LOCAL EVIRRONEMENT
            if (str_contains($thisPath, 'localhost') || str_contains($thisPath, '121.0.0.1')) $thisPath  = trim(str_replace(Constant::DOMAIN . 'public', '', $thisPath), '/');
        }

        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $thisPath);
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

    public function execute(Router $router)
    {
        if (is_callable($this->callable)) {
            // APPEL AU MIDDLEWARE 
            Middleware::resolve($this->getMiddleware(), $router);

            $action = $this->callable;
            return $action();
        }

        if (is_array($this->callable)) {
            [$className, $method] = $this->callable;

            if (class_exists($className)) {
                if (method_exists($className, $method)) {
                    $class = new $className();
                    // APPEL AU MIDDLEWARE 
                    Middleware::resolve($this->getMiddleware(), $router);

                    return call_user_func_array([$class, $method], [...$this->matches, $router]);
                } else {
                    throw new RouterException("Method not foun for '{$method}' in '{$className}'", true);
                }
            }
            throw new RouterException("Class not foun for '{$className}'", true);
        }

        throw new RouterException("Couldn't execute any callable or controller!", true);
    }

    public function with($param, $regex)
    {
        $this->params[$param] = str_replace('(', '(?:', $regex);

        return $this;
    }

    /**
     * Set the middleware for the class object.
     *
     * @param string $middleware The middleware to be set.
     * @return $this The object with the middleware set.
     */
    public function withMiddleware(string $middlewareKey)
    {
        if (!Middleware::isValid($middlewareKey)) throw new RouterException("The given middleware '" . $middlewareKey . "' is not found", true);

        $this->setMiddleware($middlewareKey);
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

    /**
     * Get the value of path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the value of params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get the value of callable
     */
    public function getCallable()
    {
        return $this->callable;
    }
}
