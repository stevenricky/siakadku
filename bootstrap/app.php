<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // ← TAMBAHKAN INI
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => App\Http\Middleware\CheckAdmin::class,
            'guru' => App\Http\Middleware\CheckGuru::class,
            'siswa' => App\Http\Middleware\CheckSiswa::class,
            // Tambahkan middleware API
            'api.auth' => \App\Http\Middleware\ApiAuthentication::class,
            'api.rate_limit' => \App\Http\Middleware\ApiRateLimit::class,
            'api.permission' => \App\Http\Middleware\ApiPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();