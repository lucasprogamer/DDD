<?php

namespace App\Domain\Schedule\Exceptions;

use Exception;

class ScheduleAlReadyExistsException extends Exception
{
    public function __construct(string $message = 'Já existe um agendamento criado nesse horario.', int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
