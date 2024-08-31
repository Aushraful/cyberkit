<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface AuthenticationInterface
{
    public function register(object $request): array;

    public function login(object $request): array;

    public function logout(Request $request): bool;

    public function refreshToken(string $token): array;
}
