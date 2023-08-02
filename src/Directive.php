<?php

namespace Source;

use Source\Constant;

class Directive
{
    public const DIRECTIVES = [
        'PUT' => "<input type='hidden' name='" . Constant::REQUEST_METHOD_NAME . "' value='PUT' />",
        'PATCH' => "<input type='hidden' name='" . Constant::REQUEST_METHOD_NAME . "' value='PATCH' />",
        'DELETE' => "<input type='hidden' name='" . Constant::REQUEST_METHOD_NAME . "' value='DELETE' />",
    ];
}
