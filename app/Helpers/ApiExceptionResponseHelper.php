<?php

namespace App\Helpers;

use App\Traits\ApiResponse;

class ApiExceptionResponseHelper
{
    use ApiResponse;

    public function modelNotFound()
    {
        return $this->errorResponse('Resource not found', 404);
    }

    public function routeNotFound()
    {
        return $this->errorResponse('Route not found', 404, 'The requested API endpoint does not exist');
    }

    public function validationFailed($errors)
    {
        return response()->json([
            'success' => false,
            'code'    => 422,
            'message' => 'Validation failed',
            'errors'  => $errors,
        ], 422);
    }

    public function unauthenticated()
    {
        return $this->errorResponse('Unauthenticated', 401, 'Authentication token is required');
    }

    public function forbidden()
    {
        return $this->errorResponse('Forbidden', 403);
    }

    public function internalError($error = null)
    {
        $errorMessage = app()->environment('local') ? $error : 'Something went wrong';
        return $this->errorResponse('Internal server error', 500, $errorMessage);
    }
}
