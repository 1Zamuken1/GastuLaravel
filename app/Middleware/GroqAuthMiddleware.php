<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroqAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['error' => 'No autorizado'], 401);
            }
            return redirect()->route('login.form');
        }

        // Verificar que el usuario esté activo
        if (!Auth::user()->activo) {
            Auth::logout();
            return redirect()->route('login.form')->with('error', 'Cuenta desactivada');
        }

        return $next($request);
    }
}