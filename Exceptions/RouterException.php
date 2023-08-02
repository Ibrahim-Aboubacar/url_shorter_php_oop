<?php

namespace Exceptions;

use Source\Dump;
use Source\Renderer;

class RouterException extends \Exception
{
    public function __construct($message, $serverError = false)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $file = $backtrace['file'];
        $line = $backtrace['line'];

        Dump::logWithFileAndLine($message, $file, $line);

        if ($serverError) die(Renderer::error500($message));

        die(Renderer::error404($message));
    }
}
