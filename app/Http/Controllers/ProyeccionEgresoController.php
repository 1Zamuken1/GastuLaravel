<?php

namespace App\Http\Controllers;

use App\Models\proyeccionEgreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProyeccionEgresoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proyeccionEgreso = proyeccionEgreso::all();
        return view('proyeccion_egreso.index', compact('proyeccionEgreso'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $proyeccionEgreso = proyeccionEgreso::all();
       return view('proyeccion_egreso.index', compact('proyeccionEgreso'));
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'monto_programado' => 'required|numeric',
        'descripcion' => 'required|string|max:200',
        'frecuencia' => 'required|string|max:30',
        'dia_recurrencia' => 'nullable|integer|min:1|max:31',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        'activo' => 'nullable|boolean',
        'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    \App\Models\ProyeccionEgreso::create([
        'monto_programado' => $request->monto_programado,
        'descripcion' => $request->descripcion,
        'frecuencia' => $request->frecuencia,
        'dia_recurrencia' => $request->dia_recurrencia,
        'fecha_inicio' => $request->fecha_inicio,
        'fecha_fin' => $request->fecha_fin,
        'activo' => $request->activo ?? true,
        'concepto_egreso_id' => $request->concepto_egreso_id,
    ]);

    return redirect()->route('proyeccion_egreso.index')
        ->with('success', 'Proyección de egreso creada exitosamente');
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $proyeccionEgreso = proyeccionEgreso::findOrFail($id);
        return view('proyeccion_egreso.show', compact('proyeccionEgreso'));        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $proyeccionEgreso = proyeccionEgreso::findOrFail($id);
        return view('proyeccion_egreso.edit', compact('proyeccionEgreso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $proyeccionEgreso = proyeccionEgreso::findOrFail($id);

        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'monto_programado' => 'required|numeric',
            'descripcion' => 'required|string|max:200',
            'frecuencia' => 'required|string|max:30',
            'dia_recurrencia' => 'nullable|integer|min:1|max:31',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'activo' => 'nullable|boolean',
            'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $proyeccionEgreso->update([
            'monto_programado' => $request->monto_programado,
            'descripcion' => $request->descripcion,
            'frecuencia' => $request->frecuencia,
            'dia_recurrencia' => $request->dia_recurrencia,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'activo' => $request->activo ?? true,
            'concepto_egreso_id' => $request->concepto_egreso_id,
        ]);

        return redirect()->route('proyeccion_egreso.index')
            ->with('success', 'Proyección de egreso actualizada exitosamente'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $proyeccionEgreso = proyeccionEgreso::findOrFail($id);
        $proyeccionEgreso->delete();

        return redirect()->route('proyeccion_egreso.index')
            ->with('success', 'Proyección de egreso eliminada exitosamente');   
            
    }
}
