<?php

use App\Http\ApiResponse;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\UserIsAdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: [__DIR__ . '/../routes/api.php',__DIR__ . '/../routes/admin-api.php'],
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            ForceJsonResponse::class,
        ]);

        $middleware->alias([
         'admin' => UserIsAdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $e) {
            Log::error('Exception occurred: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
        })->stop();


        $exceptions->shouldRenderJsonWhen(function (Request $request) {
            return true;
        })
            ->stopIgnoring(HttpException::class)
            ->respond(function (SymfonyResponse $response) {
                if ($response->isServerError()) {
                    return ApiResponse::send(false, Response::HTTP_INTERNAL_SERVER_ERROR, "Server error");
                }

                if ($response->isNotFound()) {
                    return ApiResponse::send(false, Response::HTTP_NOT_FOUND, "Page not found");
                }

                return $response;
            });
    })->create();
