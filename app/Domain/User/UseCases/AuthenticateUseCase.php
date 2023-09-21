<?php

namespace App\Domain\User\UseCases;

use App\Domain\User\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

final class AuthenticateUseCase
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function handle(string $email, string $password)
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            throw new UnauthorizedException;
        }
        return $this->getToken(Auth::user());
    }

    private function getToken($user): array
    {
        $accessToken = $user->createToken($user->id);

        return [
            'access_token' => $accessToken->plainTextToken,
            'token_type' => 'bearer',
            'expires_in' => Carbon::now()->diffInSeconds($accessToken->accessToken->expires_at),
        ];
    }
}
