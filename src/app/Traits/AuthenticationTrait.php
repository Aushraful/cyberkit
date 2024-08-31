<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait AuthenticationTrait
{
    /**
     * Get the guard to be used during authentication.
     */
    public function guard()
    {
        return Auth::guard();
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken(string $token, mixed $ttl): array
    {
        $data = [
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'expires_in'    => $ttl ?? config('app.JWT_TTL'),
            'user'          => Auth::user()
        ];

        return $data;
    }

    public function authenticate(object $request, mixed $ttl = null): array
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {

            return $this->respondWithToken($token, $ttl);
        }

        return [];
    }
}
