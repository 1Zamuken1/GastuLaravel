<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    
    public function index()
    {
        $usuarios = Usuario::with('rol')->get(); 
        return view('admin.usuarios.index', compact('usuarios'));
    }

    
    public function create()
    {
        $roles = Rol::all(); 
        return view('admin.usuarios.create', compact('roles'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'correo'   => 'required|string|email|max:255|unique:usuario,correo',
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'rol_id'   => 'required|exists:rol,rol_id',
        ]);

        Usuario::create([
            'nombre'         => $request->nombre,
            'correo'         => $request->correo,
            'telefono'       => $request->telefono,
            'password'       => $request->password,
            'fecha_registro' => now(),
            'activo'         => true,
            'rol_id'         => $request->rol_id,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $roles   = Rol::all();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre'   => 'required|string|max:255',
            'correo'   => 'required|string|email|max:255|unique:usuario,correo,' . $usuario->usuario_id . ',usuario_id',
            'telefono' => 'nullable|string|max:20',
            'rol_id'   => 'required|exists:rol,rol_id',
            'activo' => 'required|in:0,1',
        ]);

        $usuario->update([
            'nombre'   => $request->nombre,
            'correo'   => $request->correo,
            'telefono' => $request->telefono,
            'rol_id'   => $request->rol_id,
            'activo'   => $request->activo, 
            
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    public function show($id)
{
    $usuario = Usuario::with('rol')->findOrFail($id);

    return view('admin.usuarios.show', compact('usuario'));

}


    
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }
}
