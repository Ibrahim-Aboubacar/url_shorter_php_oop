<?php

namespace Source;

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
        $router = $_SESSION['router'];
        define('DOMAIN', Constant::DOMAIN);


        if ($this->error === 0) require BASE_VIEW_PATH . 'parts/header.php';

        require BASE_VIEW_PATH . $this->viewPath . '.php';

        if ($this->error === 0) require BASE_VIEW_PATH . 'parts/footer.php';

        return ob_get_clean();
    }

    public static function make(string $viewPath, array $params = []): static
    {
        return new static($viewPath, $params);
    }

    public static function error404(string $message = null)
    {
        return new static('errors/404', ['message' => $message], 404);
    }

    public static function error500(string $message = null)
    {
        return new static('errors/500', ['message' => $message], 500);
    }

    public function __toString()
    {
        return $this->view();
    }
}
