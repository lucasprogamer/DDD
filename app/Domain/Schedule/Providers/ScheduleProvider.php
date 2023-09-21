<?php

namespace App\Domain\Schedule\Providers;

use App\Domain\Schedule\Interfaces\ScheduleRepositoryInterface;
use App\Domain\Schedule\Repositories\ScheduleRepository;
use Illuminate\Support\ServiceProvider;

class ScheduleProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
