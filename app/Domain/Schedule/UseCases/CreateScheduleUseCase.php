<?php

namespace App\Domain\Schedule\UseCases;

use App\Domain\Schedule\Enums\ScheduleStatusEnum;
use App\Domain\Schedule\Exceptions\ScheduleAlreadyExistsException;
use App\Domain\Schedule\Interfaces\ScheduleRepositoryInterface;
use Carbon\Carbon;

class CreateScheduleUseCase
{
    public function __construct(
        private readonly ScheduleRepositoryInterface $scheduleRepository,
        private readonly CheckIfScheduleExistsUseCase $checkIfScheduleExists,
        private readonly CheckWeekendUseCase $checkWeekendUseCase
    ) {
    }

    public function handle(array $params)
    {
        $this->validateParams($params);
        $params = $this->setDefaultEndTime($params);
        $params['status'] = ScheduleStatusEnum::OPEN->value;

        // two fase commit
        if ($this->checkIfAlreadyExists($params)) {
            throw new ScheduleAlreadyExistsException;
        }

        return $this->scheduleRepository->create($params);
    }

    private function validateParams(array $params)
    {
        if (!isset($params['starts_at'])) {
            throw new \InvalidArgumentException("Missing 'starts_at' parameter");
        }

        if (!isset($params['user_id'])) {
            throw new \InvalidArgumentException("Missing 'user_id' parameter");
        }

        if ($this->checkWeekendUseCase->handle($params['starts_at'])) {
            throw new \InvalidArgumentException("starts_at parameter is a weekend date");
        }

        if (array_key_exists('ends_at', $params) && $this->checkWeekendUseCase->handle($params['ends_at'])) {
            throw new \InvalidArgumentException("ends_at parameter is a weekend date");
        }
    }

    private function checkIfAlreadyExists(array $params): bool
    {
        $starts_at = new Carbon($params['starts_at']);
        $ends_at = new Carbon($params['ends_at']);

        return $this->checkIfScheduleExists->handle($params['user_id'], $starts_at, $ends_at);
    }

    private function setDefaultEndTime(array $params): array
    {
        if (!array_key_exists('ends_at', $params)) {
            $starts_at = new Carbon($params['starts_at']);
            $params['ends_at'] = $starts_at->addHour()->format('Y-m-d H:i:s');
        }

        return $params;
    }
}
