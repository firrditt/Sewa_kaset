<?php

namespace App\Exceptions;

use Exception;

class UnauthorizeException extends Exception
{
    protected $errorCode = "unauthorized";

    final function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
