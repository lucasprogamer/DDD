<?php

namespace App\Domain\User\Providers;

use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class UserProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
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
