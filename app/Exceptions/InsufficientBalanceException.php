<?php

namespace App\Exceptions;

use Exception;

class InsufficientBalanceException extends Exception
{
    public function __construct($message = "Insufficient balance for this transaction.")
    {
        // Call the base class constructor with the custom message
        parent::__construct($message);
    }
}