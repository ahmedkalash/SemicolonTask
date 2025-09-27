<?php

use App\Http\Middleware\CheckPermission;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        api: __DIR__.'/../routes/adminApi.php',
        apiPrefix: 'api/v1/admin'
    )
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->alias([
            'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'checkPermission' => CheckPermission::class,
        ]); 
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
