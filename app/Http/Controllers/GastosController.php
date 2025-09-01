<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Gastos;
use App\Models\ConceptoEgreso;
use Illuminate\Http\Request;

class GastosController extends Controller
{
    // GET: /api/gastos
    public function index()
{
    $gastos = Gastos::all();
    $conceptoEgresos = ConceptoEgreso::all();

    return view('gastos', compact('gastos', 'conceptoEgresos'));
}


    // POST: /api/gastos
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

    // GET: /api/gastos/{id}
    public function show($id)
    {
        $gasto = Gastos::find($id);

        if (!$gasto) {
            return response()->json([
                'status' => 404,
                'message' => 'Gasto no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'gasto' => $gasto
        ], 200);
    }

    // PUT/PATCH: /api/gastos/{id}
    public function update(Request $request, $id)
    {
        $gasto = Gastos::find($id);

        if (!$gasto) {
            return response()->json([
                'status' => 404,
                'message' => 'Gasto no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo' => 'sometimes|required|string|max:50',
            'monto' => 'sometimes|required|numeric',
            'descripcion' => 'sometimes|nullable|string|max:255',
            'fecha_registro' => 'sometimes|required|date',
            'concepto_egreso_id' => 'sometimes|required|integer|exists:concepto_egreso,concepto_egreso_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $gasto->update($request->only([
            'tipo',
            'monto',
            'descripcion',
            'fecha_registro',
            'concepto_egreso_id'
        ]));

        return response()->json([
            'status' => 200,
            'message' => 'Gasto actualizado exitosamente',
            'gasto' => $gasto
        ], 200);
    }

    // DELETE: /api/gastos/{id}
    public function destroy($id)
    {
        $gasto = Gastos::find($id);

        if (!$gasto) {
            return response()->json([
                'status' => 404,
                'message' => 'Gasto no encontrado'
            ], 404);
        }

        $gasto->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Gasto eliminado exitosamente'
        ], 200);
    }
}
