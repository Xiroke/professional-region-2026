<?php

use App\Http\Middleware\ForceJsonMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'school-api'
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prependToGroup('api', ForceJsonMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('school-api/*')) {
                return true;
            }
            return $request->expectsJson();
        });
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('school-api/*') || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Forbidden for you'
                ], 403);
            }
        });
    })->create();
