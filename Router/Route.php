<?php

namespace Router;

use Exceptions\RouterException;

class Route
{
    private $path;
    private $matches = [];
    private $params = [];
    private $withAuthentication = false;

    public function __construct($path, private $callable)
    {
        $this->path = trim($path, '/');
    }

    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        // '([^/]+)'
        // CASE SENSITIVE AVEC LE i ET CASE INSENSITIVE SANS LE i
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
        if ($this->withAuthentication && !isset($_SESSION['auth']['id'])) {
            // Renvoyer le code de réponse 401 Unauthorized
            http_response_code(401);
            header('location: ' . $router->url('user.login'));
            die;
        }


        if (is_callable($this->callable)) {
            $action = $this->callable;
            return $action();
        }

        if (is_array($this->callable)) {
            [$className, $method] = $this->callable;

            if (class_exists($className) && method_exists($className, $method)) {
                $class = new $className();

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

    public function withAuth()
    {
        $this->withAuthentication = true;

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
}
