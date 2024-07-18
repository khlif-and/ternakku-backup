<?php

namespace App\Exceptions;

use Exception;

class DuplicateEartagException extends Exception
{
    public function __construct($message = "Duplicate eartag found.")
    {
        parent::__construct($message);
    }
}
