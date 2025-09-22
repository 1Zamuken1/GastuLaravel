<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GroqAuthMiddleware
{
    // public function handle(Request $request, Closure $next)
    // {
    //     // Si no hay usuario autenticado
    //     if (!Auth::check()) {
    //         return redirect()->route('login.form'); // si no está logueado
    //     }

    //     // Si el usuario existe pero está desactivado
    //     if (!Auth::user()->activo) {
    //         Auth::logout();
    //         return redirect()->route('login.form')
    //                          ->with('error', 'Cuenta desactivada');
    //     }

    //     // Si todo bien -> continuar
    //     return $next($request);
    // }
    
        public function handle(Request $request, Closure $next)
    {
        // Debug: Log información de autenticación
        $user = Auth::user();
        Log::info('GroqAuth Debug', [
            'auth_check' => Auth::check(),
            'user_id' => $user ? $user->usuario_id : null,
            'user_email' => $user ? $user->correo : null,
            'user_active' => $user ? $user->activo : null,
            'session_id' => $request->session()->getId(),
            'route' => $request->route()->getName()
        ]);

        // Si no hay usuario autenticado
        if (!Auth::check()) {
            Log::info('Usuario no autenticado, redirigiendo al login');
            
            if ($request->ajax()) {
                return response()->json(['error' => 'No autorizado'], 401);
            }
            return redirect()->route('login.form')
                             ->with('error', 'Debes iniciar sesión');
        }

        // Si el usuario existe pero está desactivado
        if (!$user->activo) {
            Log::info('Usuario desactivado', ['user_id' => $user->usuario_id]);
            Auth::logout();
            return redirect()->route('login.form')
                             ->with('error', 'Cuenta desactivada');
        }

        Log::info('Usuario autorizado correctamente', ['user_id' => $user->usuario_id]);

        // Si todo bien -> continuar
        return $next($request);
    
    }
    
}
