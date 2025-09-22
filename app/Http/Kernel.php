<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * Estos middleware se ejecutan en cada petición.
     */
    protected $middleware = [
        // Manejo de proxies (si usas Heroku, etc.)
        \App\Http\Middleware\TrustProxies::class,

        // Evita requests cuando la app está en mantenimiento
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,

        // Validación del tamaño de las requests
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        // Convierte inputs vacíos en null
        \App\Http\Middleware\TrimStrings::class,

        // Convierte strings vacíos en null automáticamente
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // rate limit (ejemplo: 60 por minuto)
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * Aquí registras alias de middleware que luego usas en tus rutas.
     */
    protected $middlewareAliases = [
        'auth'       => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'        => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'      => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed'     => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'   => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'   => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // 👇 Aquí tu middleware personalizado
        'groq.auth'  => \App\Http\Middleware\GroqAuthMiddleware::class,
    ];
}
