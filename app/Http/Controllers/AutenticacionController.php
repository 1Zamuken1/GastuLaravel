<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutenticacionController extends Controller
{
    /**
     * Registro de usuario
     */
    public function registrar(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'correo'   => 'required|string|email|max:255|unique:usuario,correo',
            'telefono' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario = Usuario::create([
            'nombre'         => $request->nombre,
            'correo'         => $request->correo,
            'telefono'       => $request->telefono,
            'password'       => $request->password, // el mutator lo encripta
            'fecha_registro' => now(),
            'activo'         => true,
            'rol_id'         => 3,
        ]);

        Auth::login($usuario);

        return redirect()->route('dashboard');
    }

    /**
     * Login de usuario
     */
    public function login(Request $request)
{
    $credentials = $request->validate([
        'correo'   => 'required|email',
        'password' => 'required|string',
    ]);

    // Especifica el campo de identificación
    if (Auth::attempt(['correo' => $credentials['correo'], 'password' => $credentials['password']])) {
        $request->session()->regenerate();

        $usuario = Auth::user();

        // Redirección según rol
        if ($usuario->rol_id == 1) { // Admin
            return redirect()->route('usuarios.index');
        }

        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'correo' => 'Las credenciales no son correctas.',
    ])->onlyInput('correo');
}

    /**
     * Logout de usuario
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}
