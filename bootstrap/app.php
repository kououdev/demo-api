<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use App\Helpers\ApiExceptionResponseHelper;

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
        $helper = new ApiExceptionResponseHelper();

        // Global Report Handler
        $exceptions->report(function (Throwable $e) {
            // Bisa kirim ke Sentry, Bugsnag, atau custom logger
            // logger()->error($e->getMessage(), ['exception' => $e]);
        });

        // Model Not Found → 404 JSON untuk API
        $exceptions->render(function (ModelNotFoundException $e, $request) use ($helper) {
            if ($request->is('api/*')) {
                return $helper->modelNotFound();
            }
        });

        // Route Not Found → 404 JSON untuk API
        $exceptions->render(function (NotFoundHttpException $e, $request) use ($helper) {
            if ($request->is('api/*')) {
                return $helper->routeNotFound();
            }
        });

        // Validation Error → 422 JSON untuk API
        $exceptions->render(function (ValidationException $e, $request) use ($helper) {
            if ($request->is('api/*')) {
                return $helper->validationFailed($e->errors());
            }
        });

        // Authentication Error → 401 JSON untuk API
        $exceptions->render(function (AuthenticationException $e, $request) use ($helper) {
            if ($request->is('api/*')) {
                return $helper->unauthenticated();
            }
        });

        // Route Not Defined Error (untuk login route yang tidak ada) → 401 JSON untuk API
        $exceptions->render(function (RouteNotFoundException $e, $request) use ($helper) {
            if ($request->is('api/*')) {
                // Jika error karena route 'login' not defined, ini berarti user tidak authenticated
                if (str_contains($e->getMessage(), 'Route [login] not defined')) {
                    return $helper->unauthenticated();
                }

                // Untuk route not found lainnya
                return $helper->routeNotFound();
            }
        });

        // Authorization Error → 403 JSON untuk API
        $exceptions->render(function (AuthorizationException $e, $request) use ($helper) {
            if ($request->is('api/*')) {
                return $helper->forbidden();
            }
        });

        // Default Fallback untuk API
        $exceptions->render(function (Throwable $e, $request) use ($helper) {
            if ($request->is('api/*')) {
                return $helper->internalError($e->getMessage());
            }
        });
    })->create();
