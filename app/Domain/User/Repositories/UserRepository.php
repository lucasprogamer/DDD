<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Domain\User\Models\User;
use App\Infra\Repositories\AbstractRepository;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
