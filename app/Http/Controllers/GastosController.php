<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Gastos;
use App\Models\ConceptoEgreso;
use Illuminate\Http\Request;

class GastosController extends Controller
{
    // Mostrar listado de gastos
    public function index()
    {
        $gastos = Gastos::all();
        return view('gastos.index', compact('gastos'));
    }

    // Mostrar formulario para crear un nuevo gasto
    public function create()
    {   
        $gastos = Gastos::all();
        return view('gastos.create', compact('gastos'));
    }

    // Guardar un nuevo gasto
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|string|max:50',
            'monto' => 'required|numeric',
            'descripcion' => 'nullable|string|max:255',
            'fecha_registro' => 'required|date',
            'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Gastos::create($request->only([
            'tipo',
            'monto',
            'descripcion',
            'fecha_registro',
            'concepto_egreso_id'
        ]));

        return redirect()->route('gastos.index')
            ->with('success', 'Gasto creado exitosamente');
    }

    // Mostrar un gasto en detalle
    public function show($id)
    {
        $gasto = Gastos::findOrFail($id);
        return view('gastos.show', compact('gasto'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $gasto = Gastos::findOrFail($id);
        return view('gastos.edit', compact('gasto'));
    }

    // Actualizar un gasto existente
    public function update(Request $request, $id)
    {
        $gasto = Gastos::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tipo' => 'required|string|max:50',
            'monto' => 'required|numeric',
            'descripcion' => 'nullable|string|max:255',
            'fecha_registro' => 'required|date',
            'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $gasto->update($request->only([
            'tipo',
            'monto',
            'descripcion',
            'fecha_registro',
            'concepto_egreso_id'
        ]));

        return redirect()->route('gastos.index')
            ->with('success', 'Gasto actualizado exitosamente');
    }

    // Eliminar un gasto
    public function destroy($id)
    {
        $gasto = Gastos::findOrFail($id);
        $gasto->delete();

        return redirect()->route('gastos.index')
            ->with('success', 'Gasto eliminado exitosamente');
    }
}
