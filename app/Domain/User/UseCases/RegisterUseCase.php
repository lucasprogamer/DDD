<?php

namespace App\Domain\User\UseCases;

use App\Domain\User\Interfaces\UserRepositoryInterface;

final class RegisterUseCase
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function handle(array $params)
    {
        return $this->userRepository->create($params);
    }
}
