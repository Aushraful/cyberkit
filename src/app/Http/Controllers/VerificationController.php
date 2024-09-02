<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\VerificationInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use ApiResponseTrait;

    protected $verificationRepository;

    public function __construct(VerificationInterface $verificationInterface)
    {
        $this->verificationRepository = $verificationInterface;
    }

    public function isEmailVerified(): JsonResponse
    {
        try {
            $response = $this->verificationRepository->isEmailVerified();

            return $response;
        } catch (\Exception $e) {
            // throw $e;
            return self::apiServerError($e->getMessage());
        }
    }

    public function verifyEmail(Request $request): JsonResponse
    {
        try {
            $response = $this->verificationRepository->verifyEmail($request);

            return $response;
        } catch (\Exception $e) {
            // throw $e;
            return self::apiServerError($e->getMessage());
        }
    }

    public function resendVerificationEmail(Request $request): JsonResponse
    {

        try {
            $response = $this->verificationRepository->resendVerificationEmail($request);

            return $response;
        } catch (\Exception $e) {
            // throw $e;
            return self::apiServerError($e->getMessage());
        }
    }
}
