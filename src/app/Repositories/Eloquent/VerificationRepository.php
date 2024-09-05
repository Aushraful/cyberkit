<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\VerificationInterface;
use App\Repositories\Eloquent\Common\BaseRepository;
use App\Traits\ApiResponseTrait;
use App\Traits\AuthenticationTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class VerificationRepository extends BaseRepository implements VerificationInterface
{
    use ApiResponseTrait, AuthenticationTrait;

    /**
     * Create a new repository instance.
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function isEmailVerified(): JsonResponse
    {
        $data = $this->guard()->user();

        $message = 'Email is verified!';

        if ($data->email_verified_at == null) {
            $message = 'Email not verified!';

            return self::apiResponseError(Response::HTTP_ACCEPTED, $message);
        }

        return self::apiResponseSuccess(Response::HTTP_OK, $message, collect($data));
    }

    public function verifyEmail(object $request): JsonResponse
    {
        $user = $this->model->find($request->id);
        $expires = Carbon::createFromTimestamp($request->expires);
        $isExpired = Carbon::now()->gt($expires);

        if ($isExpired) {

            return self::apiResponseError(Response::HTTP_ACCEPTED, 'The link has already expired! Please resend and try again');
        }

        if ($request->hasValidSignature(false)) {

            if ($user->email_verified_at == null) {
                $user->email_verified_at = Carbon::now(); // to enable the “email_verified_at field of that user be a current time stamp by mimicing the must verify email feature
                $user->save();
            }

            return self::apiResponseSuccess(Response::HTTP_OK, 'Email verified Successfully!', collect($user));
        }

        return self::apiResponseError(Response::HTTP_UNPROCESSABLE_ENTITY, 'Provided data is not valid!');
    }

    public function resendVerificationEmail(object $request): JsonResponse
    {
        $user = $this->model->find($request->id);

        if (!$user->email == $request->email) {

            return self::apiResponseError(Response::HTTP_OK, 'Email does not match!');
        }

        if ($user->hasVerifiedEmail()) {

            return self::apiResponseError(Response::HTTP_OK, 'Email already varified!');
        }

        $user->sendEmailVerificationNotification();

        return self::apiResponseSuccess(Response::HTTP_OK, 'Verification email sent successfully!', collect($request->user));
    }
}
