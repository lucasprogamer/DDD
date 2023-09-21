<?php

namespace App\Domain\Schedule\UseCases;

use App\Domain\Schedule\Interfaces\ScheduleRepositoryInterface;
use Carbon\Carbon;

final class CheckIfScheduleExistsUseCase
{
    public function __construct(private readonly ScheduleRepositoryInterface $scheduleRepository)
    {
    }

    public function handle(int $user_id, Carbon $starts_at, Carbon $ends_at = null): bool
    {
        return $this->scheduleRepository->checkIfDateScheduleExists($user_id, $starts_at, $ends_at ?? $starts_at);
    }
}
