<?php 

namespace App\Exceptions;

use Exception;

class InvalidTransactionTypeException extends Exception
{
    public function __construct($message = "The transaction type is invalid.")
    {
        // Call the base class constructor with the custom message
        parent::__construct($message);
    }
}