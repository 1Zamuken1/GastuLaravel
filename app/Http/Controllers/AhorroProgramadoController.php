<?php

namespace App\Http\Controllers;

use App\Models\AhorroProgramado;
use App\Models\AhorroMeta;
use Illuminate\Http\Request;

class AhorroProgramadoController extends Controller
{
    // Listar todos los programados de una meta de ahorro
    public function index($ahorroMetaId)
    {
        $meta = AhorroMeta::with('ahorroProgramados')->findOrFail($ahorroMetaId);
        $programados = $meta->ahorroProgramados;

        return view('programados.index', compact('meta', 'programados'));
    }

    // Mostrar formulario de creación de programado
    public function create($ahorroMetaId)
    {
        $meta = AhorroMeta::findOrFail($ahorroMetaId);
        return view('programados.create', compact('meta'));
    }

    // Guardar programado
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ahorro_meta_id' => 'required|integer|exists:ahorro_meta,ahorro_meta_id',
            'monto_programado' => 'required|numeric|min:1',
            'frecuencia' => 'required|string|max:30',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'num_cuotas' => 'nullable|integer|min:1'
        ]);

        AhorroProgramado::create($validated);

        return redirect()->route('programados.index', $validated['ahorro_meta_id'])
                         ->with('success', 'Ahorro programado creado correctamente.');
    }

    // Mostrar detalle de un programado
    public function show($id)
    {
        $programado = AhorroProgramado::with('ahorro_meta')->findOrFail($id);
        return view('programados.show', compact('programado'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $programado = AhorroProgramado::with('ahorro_meta')->findOrFail($id);
        return view('programados.edit', compact('programado'));
    }

    // Actualizar programado
    public function update(Request $request, $id)
    {
        $programado = AhorroProgramado::findOrFail($id);

        $validated = $request->validate([
            'monto_programado' => 'required|numeric|min:1',
            'frecuencia' => 'required|string|max:30',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'num_cuotas' => 'nullable|integer|min:1'
        ]);

        $programado->update($validated);

        return redirect()->route('programados.index', $programado->ahorro_meta_id)
                         ->with('success', 'Ahorro programado actualizado correctamente.');
    }

    // Eliminar programado
    public function destroy($id)
    {
        $programado = AhorroProgramado::findOrFail($id);
        $ahorroMetaId = $programado->ahorro_meta_id;
        $programado->delete();

        return redirect()->route('programados.index', $ahorroMetaId)
                         ->with('success', 'Ahorro programado eliminado correctamente.');
    }
}
