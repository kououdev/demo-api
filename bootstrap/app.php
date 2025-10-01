<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',   // tambahkan baris ini
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Global Report Handler
        $exceptions->report(function (Throwable $e) {
            // Bisa kirim ke Sentry, Bugsnag, atau custom logger
            // logger()->error($e->getMessage(), ['exception' => $e]);
        });

        // Model Not Found → 404 JSON untuk API
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'code' => '404',
                    'success' => false,
                    'message' => 'Resource not found',
                ], 404);
            }
        });

        // Route Not Found → 404 JSON untuk API
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'code' => '404',
                    'success' => false,
                    'message' => 'Route not found',
                    'error' => 'The requested API endpoint does not exist'
                ], 404);
            }
        });

        // Validation Error → 422 JSON untuk API
        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'code' => '422',
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Authentication Error → 401 JSON untuk API
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'code' => '401',
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }
        });

        // Authorization Error → 403 JSON untuk API
        $exceptions->render(function (AuthorizationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'code' => '403',
                    'success' => false,
                    'message' => 'Forbidden',
                ], 403);
            }
        });

        // Default Fallback untuk API
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'code' => '500',
                    'success' => false,
                    'message' => 'Internal server error',
                    'error'   => app()->environment('local') ? $e->getMessage() : 'Something went wrong',
                ], 500);
            }
        });
    })->create();
