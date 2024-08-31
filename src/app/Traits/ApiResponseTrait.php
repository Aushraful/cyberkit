<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Http\Response;

trait ApiResponseTrait
{
    private $successStatus = 'Success';
    private $errorStatus = 'Error';

    public function __construct()
    {
        //
    }

    public function apiResponseSuccess(int $code, string $message, Collection $data)
    {
        return response()->json([
            'status' => $this->successStatus,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function apiResponseError(int $code, string $message)
    {
        return response()->json([
            'status' => $this->errorStatus,
            'code' => $code,
            'message' => $message,
        ], $code);
    }

    public function apiServerError(string $message)
    {
        $modelNotFoundMessage = 'No query results for model';

        $errorCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        $parsedMessage = explode(' [', $message);

        if ($modelNotFoundMessage == $parsedMessage[0]) {
            $message = $modelNotFoundMessage;
            $errorCode = 404;
        }

        return response()->json([
            'status' => $this->errorStatus,
            'code' => $errorCode,
            'message' => $message,
        ], $errorCode);
    }
}
