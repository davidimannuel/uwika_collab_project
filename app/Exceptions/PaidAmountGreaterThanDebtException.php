<?php

namespace App\Exceptions;

use Exception;

class PaidAmountGreaterThanDebtException extends Exception
{
    public function __construct($message = "Paid amount greater than debt")
    {
        // Call the base class constructor with the custom message
        parent::__construct($message);
    }
}