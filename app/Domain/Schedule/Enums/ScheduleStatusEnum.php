<?php

namespace App\Domain\Schedule\Enums;

enum ScheduleStatusEnum: string
{
    case OPEN = 'aberto';
    case CLOSED = 'concluído';
}
