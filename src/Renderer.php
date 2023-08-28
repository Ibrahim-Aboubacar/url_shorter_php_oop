<?php

namespace Source;

use Error;
use Exceptions\RouterException;
use Source\Constant;

class Renderer
{
    public function __construct(private string $viewPath, private ?array $params, private $error = 0)
    {
    }

    public function view(): string
    {
        ob_start();

        if (!is_null($this->params)) extract($this->params);

        // VAR DEFINITION
        /**
         * @var Router $route
         */
        // $router = App::getRouter();
        define('ROUTER', App::getRouter());
        define('DOMAIN', Constant::DOMAIN);
        define('APP_NAME', App::getAppName());

        if ($this->error === 0) require BASE_VIEW_PATH . 'parts/header.view.php';

        require BASE_VIEW_PATH . $this->viewPath . '.view.php';

        if ($this->error === 0) require BASE_VIEW_PATH . 'parts/footer.view.php';

        $html = self::rendDrirectives(ob_get_clean());
        die($html);
        exit;
    }

    public static function make(string $viewPath, array $params = []): static
    {
        return new static($viewPath, $params);
    }

    public static function error404(string $message = null)
    {
        http_response_code(404);
        return new static('errors/404', ['message' => $message ?? 'NOT FOUND'], 404);
    }

    public static function error500(string $message = null)
    {
        http_response_code(4500);
        return new static('errors/500', ['message' => $message ?? 'SERVER SIDE ERROR'], 500);
    }

    public function __toString()
    {
        return $this->view();
    }

    public static function rendDrirectives($html): string
    {
        foreach (Directive::DIRECTIVES as $key => $value) {
            // Expression régulière pour trouver @PUT, @PATCH ou @DELETE
            $regex = '/@' . preg_quote($key, '/') . '/';

            $html = preg_replace($regex, $value, $html);
        }
        return $html;
    }
};