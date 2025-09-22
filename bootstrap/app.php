<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\GroqAuthMiddleware; // 👈 importa tu middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 👇 Alias de middleware para usarlo en rutas
        $middleware->alias([
            'groq.auth' => GroqAuthMiddleware::class,
        ]);

        // (Opcional) Si quieres aplicarlo automáticamente a todas las rutas "web":
        // $middleware->appendToGroup('web', [
        //     GroqAuthMiddleware::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
