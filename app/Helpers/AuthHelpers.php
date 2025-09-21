<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    public static function userId()
    {
        return Auth::id(); 
    }
}
