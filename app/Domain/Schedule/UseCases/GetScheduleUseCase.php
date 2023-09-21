<?php

namespace App\Domain\Schedule\UseCases;

use App\Domain\Schedule\Data\SearchScheduleDto;
use App\Domain\Schedule\Interfaces\ScheduleRepositoryInterface;

final class GetScheduleUseCase
{
    public function __construct(private readonly ScheduleRepositoryInterface $scheduleReposity)
    {
    }

    public function handle(SearchScheduleDto $dto)
    {
        return $this->scheduleReposity->search($dto);
    }
}
