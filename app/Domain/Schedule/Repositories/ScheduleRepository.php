<?php

namespace App\Domain\Schedule\Repositories;

use App\Domain\Schedule\Data\SearchScheduleDto;
use App\Domain\Schedule\Interfaces\ScheduleRepositoryInterface;
use App\Domain\Schedule\Models\Schedule;
use App\Infra\Repositories\AbstractRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ScheduleRepository extends AbstractRepository implements ScheduleRepositoryInterface
{
    public function __construct(Schedule $model)
    {
        parent::__construct($model);
    }

    public function search(SearchScheduleDto $searchDto): Collection
    {
        $query = $this->model->where('user_id', $searchDto->user_id);

        if ($searchDto->starts_at && $searchDto->ends_at) {
            $query->dateBetween($searchDto->starts_at, $searchDto->ends_at);
        } elseif ($searchDto->starts_at) {
            $this->applyDateFilter($query, $searchDto->starts_at);
        }
        if ($searchDto->title) {
            $query->where('title', $searchDto->title);
        }

        return $query->get();
    }

    public function checkIfDateScheduleExists(int $id, Carbon $starts_at, Carbon $ends_at): bool
    {
        return $this->model->dateBetween($starts_at, $ends_at)->exists();
    }

    protected function applyDateFilter($query, Carbon $date)
    {
        $starts = $date->copy()->startOfDay();
        $ends = $date->copy()->endOfDay();
        $query->dateBetween($starts, $ends);
    }
}
