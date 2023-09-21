<?php

namespace App\Domain\Schedule\UseCases;

class CheckWeekendUseCase
{
    public function handle(string $date): bool
    {
        $dateTime = new \DateTime($date);
        $dayOfWeek = $dateTime->format('N');

        return in_array($dayOfWeek, [6, 7]);
    }
}
