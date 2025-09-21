<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    public static function userId()
    {
        return Auth::id() ?? 1; // Usa el usuario con ID=1 si no hay login
    }
}
