<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        // Original:
        // 'guard' => env('AUTH_GUARD', 'web'),
        // 'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),

        // Nuevo:
        'guard' => 'web',
        'passwords' => 'usuarios',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        'web' => [
            'driver' => 'session',

            // Original:
            // 'provider' => 'users',

            // Nuevo:
            'provider' => 'usuarios',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        // Original:
        // 'users' => [
        //     'driver' => 'eloquent',
        //     'model' => env('AUTH_MODEL', App\Models\User::class),
        // ],

        // Nuevo:
        'usuarios' => [
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        // Original:
        // 'users' => [
        //     'provider' => 'users',
        //     'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
        //     'expire' => 60,
        //     'throttle' => 60,
        // ],

        // Nuevo:
        'usuarios' => [
            'provider' => 'usuarios',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
