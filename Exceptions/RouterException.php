<?php

namespace Exceptions;

use Source\Renderer;

class RouterException extends \Exception
{
    public function __construct($message)
    {
        echo Renderer::error404($message);
    }
}
