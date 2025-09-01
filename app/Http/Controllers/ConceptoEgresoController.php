<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\ConceptoEgreso;
use Illuminate\Http\Request;

class ConceptoEgresoController extends Controller
{
    public function index()
    {
        $conceptoEgresos = ConceptoEgreso::all();
       return view('gastos', compact('conceptoEgresos'));
    }

    public function create()
    {
        $conceptos = ConceptoEgreso::all();
        return response()->json([
            'status' => 200,
            'conceptos' => $conceptos
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $concepto = ConceptoEgreso::create($request->only([
            'nombre',
            'descripcion'
        ]));

        return response()->json([
            'status' => 201,
            'message' => 'Concepto de egreso creado exitosamente',
            'concepto' => $concepto
        ], 201);
    }

    public function show(ConceptoEgreso $conceptoEgreso)
    {
        return response()->json([
            'status' => 200,
            'concepto' => $conceptoEgreso
        ], 200);
    }

    public function edit(ConceptoEgreso $conceptoEgreso)
    {
        return response()->json([
            'status' => 200,
            'concepto' => $conceptoEgreso
        ], 200);
    }

    public function update(Request $request, ConceptoEgreso $concepto)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:100',
            'descripcion' => 'sometimes|nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $concepto->update($request->only([
            'nombre',
            'descripcion'
        ]));

        return response()->json([
            'status' => 200,
            'message' => 'Concepto de egreso actualizado exitosamente',
            'concepto' => $concepto
        ], 200);
    }

    public function destroy(ConceptoEgreso $conceptoEgreso)
    {
        $conceptoEgreso->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Concepto de egreso eliminado exitosamente'
        ], 200);
    }
}