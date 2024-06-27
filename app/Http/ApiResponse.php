<?php

declare(strict_types=1);

namespace App\Http;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Return a successful JSON response with mixed(object|JsonSerializable) data
     *
     * @param int $code
     * @param string|null $message
     * @param mixed $data
     * @return JsonResponse
     */
    public static function send(bool $status, int $code, string $message = null, $data = []): JsonResponse
    {

        $response = [
          'status' => $status,
          'message' => $message,
          'data' => $data
        ];
        return response()->json($response, $code, [], JSON_PRESERVE_ZERO_FRACTION);
    }
    /**
       * Set success response
       *
       * @param $message
       * @param mixed $data
       *
       * @return JsonResponse
       */
    public static function sendWithResource(bool $status, int $code, string $message = null, $data): JsonResponse
    {
        $response = [
            'status' => $status,
            'message' => $message
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Set success response
     *
     * @param $message
     * @param $collection
     *
     * @return JsonResponse
     */
    public static function sendWithCollection(bool $status, int $code, string $message = null, $collection): JsonResponse
    {
        return response()->json(array_merge([
            'status' => $status,
            'message' => $message,
        ], (array)$collection), $code);
    }

}
