<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse(string $message = 'Error', int $code = 400, $error = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => $code,
            'message' => $message,
            'error'   => $error,
        ], $code);
    }

    protected function paginatedResponse($paginator, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'code'    => 200,
            'message' => $message,
            'data'    => $paginator->items(),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ], 200);
    }
}
