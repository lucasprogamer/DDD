<?php

namespace App\Domain\Schedule\UseCases;

use App\Domain\Schedule\Interfaces\ScheduleRepositoryInterface;

final class DeleteScheduleUseCase
{
    public function __construct(private readonly ScheduleRepositoryInterface $scheduleRepository)
    {
    }

    public function handle(int $id)
    {
        return $this->scheduleRepository->delete($id);
    }
}
