<?php

namespace App\Domain\User\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct(string $message = 'User not found', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}
