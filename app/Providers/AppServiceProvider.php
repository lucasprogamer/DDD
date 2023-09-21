<?php

namespace App\Providers;

use App\Domain\Schedule\Providers\ScheduleProvider;
use App\Domain\User\Providers\UserProvider;
use App\Infra\Repositories\AbstractRepository;
use App\Infra\Repositories\AbstractRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AbstractRepositoryInterface::class, AbstractRepository::class);
        $this->app->register(UserProvider::class);
        $this->app->register(ScheduleProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
