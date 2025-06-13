<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
       $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'checkAdminHasRt' => \App\Http\Middleware\CheckAdminHasRtMiddleware::class, // <-- Tambahkan ini
            'isWargaWithRt' => \App\Http\Middleware\IsWargaWithRtMiddleware::class, // <-- Tambahkan ini
            // alias middleware lain yang sudah ada dari Breeze atau paket lain akan ada di sini
            // contoh: 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]); //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
