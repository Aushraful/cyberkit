<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegistrationRequest;
use App\Repositories\Contracts\AuthenticationInterface;
use App\Traits\ApiResponseTrait;
use App\Traits\AuthenticationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    use ApiResponseTrait, AuthenticationTrait;

    protected $authenticationRepository;

    public function __construct(AuthenticationInterface $authenticationInterface)
    {
        $this->authenticationRepository = $authenticationInterface;
    }

    public function register(RegistrationRequest $request): JsonResponse
    {
        try {
            $data = collect($this->authenticationRepository->register($request));

            if (count($data) < 1) {
                return self::apiResponseError(Response::HTTP_UNAUTHORIZED, 'Something went wrong!');
            }

            return self::apiResponseSuccess(Response::HTTP_OK, 'Successfully signed up! Please verify your email!', $data);
        } catch (\Exception $e) {
            // throw $e;
            return self::apiServerError($e->getMessage());
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->authenticationRepository->login($request);
            $data = collect($data);

            if (count($data) < 1) {
                return self::apiResponseError(Response::HTTP_UNAUTHORIZED, 'Invalid Email and/or Password!',);
            }

            return self::apiResponseSuccess(Response::HTTP_OK, 'Successfully logged in!', $data);
        } catch (\Exception $e) {
            // throw $e;
            return self::apiServerError($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {

            // dd($request);
            $success = $this->authenticationRepository->logout($request);

            if (!$success) {
                return self::apiResponseError(Response::HTTP_UNAUTHORIZED, 'Something went wrong!');
            }

            return self::apiResponseSuccess(Response::HTTP_OK, 'Successfully logged out!', collect([]));
        } catch (\Exception $e) {
            // throw $e;
            return self::apiServerError($e->getMessage());
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            // Retrieve the token from the request's authorization header
            $token = $request->bearerToken();

            $response  = collect($this->authenticationRepository->refreshToken($token));

            return self::apiResponseSuccess(Response::HTTP_OK, 'Token refreshed Successfully!', $response);
        } catch (\Exception $e) {

            return self::apiServerError($e->getMessage());
        }
    }
}
