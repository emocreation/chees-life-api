<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    final public function success(string $message = 'success', int $code = 200, $data = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }

    final public function error(string $message = 'error', int $code = 400, $data = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
