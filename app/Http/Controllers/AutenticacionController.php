<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutenticacionController extends Controller
{
    //Registro de Usuarios 

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuario,correo',
            'telefono' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'password' => $request->password, //se encripta automáticamente en el modelo
            'fecha_registro' => now(),
            'activo' => true,
            'rol_id' => 3,
        ]);

        Auth::login($usuario);

        return response()->json([
            'mensaje' => 'usuario registrado y autentucado con éxito',
            'usuario' => $usuario
        ]);
    }

    // login de usuario

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string',
        ]);

         if (Auth::attempt(['correo' => $request->correo, 'password' => $request->password])) {
        $request->session()->regenerate();

        $usuario = Auth::user();
        
         // Redirección según rol
        if ($usuario->rol_id == 1) { // Admin
            return redirect()->route('usuarios.index');
        } else {
            return redirect()->route('ingresos.index');
        }
        }

        return back()->withErrors([
        'correo' => 'Las credenciales no son correctas.',
    ])->onlyInput('correo');
    }

    //logout de usuario

    public function logout(Request $request){
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'mensaje'=> 'Sesion cerrada con éxito'
        ]);
    }
}
