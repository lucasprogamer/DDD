<?php

namespace App\Domain\Schedule\Interfaces;

use App\Domain\Schedule\Data\SearchScheduleDto;
use App\Infra\Repositories\AbstractRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface ScheduleRepositoryInterface extends AbstractRepositoryInterface
{
    public function search(SearchScheduleDto $searchDto): Collection;
    public function checkIfDateScheduleExists(int $userId, Carbon $starts_at, Carbon $ends_at): bool;
}
