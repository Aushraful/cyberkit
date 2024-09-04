<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\JsonResponse;

interface VerificationInterface
{
    public function isEmailVerified(): JsonResponse;

    public function verifyEmail(object $request): JsonResponse;

    public function resendVerificationEmail(object $request): JsonResponse;
}
