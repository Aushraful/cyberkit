<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\AuthenticationInterface;
use App\Models\User;
use App\Repositories\Eloquent\Common\BaseRepository;
use App\Traits\AuthenticationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationRepository extends BaseRepository implements AuthenticationInterface
{
    use AuthenticationTrait;

    /**
     * Create a new repository instance.
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function register(object $request): array
    {
        $data = [
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ];

        $this->model->create($data);

        $data = $this->authenticate($request);

        return $data;
    }

    public function login(object $request): array
    {
        $ttl = config('app.JWT_TTL');

        if ($request['remember_me'] == true) {
            $ttl = config('app.JWT_REMEMBER_TTL');
        }

        $data = $this->authenticate($request, $ttl);

        return $data;
    }

    public function logout(Request $request): bool
    {
        Auth::logout(); // Logs out the user.

        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return true;
    }

    public function refreshToken(string $token): array
    {
        // Refresh the token using JWTAuth
        $newToken = JWTAuth::setToken($token)->refresh();

        return $this->respondWithToken($newToken, null);
    }
}
