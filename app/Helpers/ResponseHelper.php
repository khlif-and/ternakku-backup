<?php

namespace App\Helpers;

class ResponseHelper
{
        /**
     * Wrap successful data response.
     *
     * @param mixed $data
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data,  $message = null , $httpCode = 200 )
    {
        return response()->json([
            'message' =>  $message,
            'data' => $data
        ], $httpCode);
    }

    /**
     * Wrap error response.
     *
     * @param string $message
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message, $httpCode = 500)
    {
        return response()->json(['errors' => $message], $httpCode);
    }
}
