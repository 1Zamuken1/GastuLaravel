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

        auth::login($usuario);

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

            return response()->json([
                'mensaje' => 'Login exitoso',
                'usuario' => auth::user()
            ]);
        }

        return response()->json([
            'mensaje' => 'credenciales incorrectas'
        ], 401);
    }

    //logout de usuario

    public function logout(Request $request){
        
        auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'mensaje'=> 'Sesion cerrada con éxito'
        ]);
    }
}
