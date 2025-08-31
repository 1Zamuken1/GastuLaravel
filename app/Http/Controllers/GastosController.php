<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;//Validator; una librería de laravel que nos permitirá reducir código al obligar al usuario a llenar ciertos campos
use App\Models\Gastos;
use Illuminate\Http\Request;

class GastosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gastos = Gastos::all();
        if($gastos->isEmpty()){
            $data = [
                'status' => 404,
                'message' => 'No se encontraron gastos registrados'
            ];
            return response()->json($data, 404);
        }
        $data = [
            'status' => 200,
            'gastos' => $gastos
        ];
        return response()->json($data, 200);
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gastos = Gastos::all();
        return response()->json([
            'status' => 200,
            'gastos' => $gastos
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
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
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $gasto = Gastos::create([
            'tipo' => $request->input('tipo'),
            'monto' => $request->input('monto'),
            'descripcion' => $request->input('descripcion'),
            'fecha_registro' => $request->input('fecha_registro'),
            'concepto_egreso_id' => $request->input('concepto_egreso_id'),
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Gasto creado exitosamente',
            'gasto' => $gasto
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gastos $gastos)
    {
        $gastos = Gastos::find($gastos->gasto_id);
        if(!$gastos){
            $data = [
                'status' => 404,
                'message' => 'Gasto no encontrado'
            ];
            return response()->json($data, 404);    
        }
        $data = [
            'status' => 200,
            'gasto' => $gastos
        ];
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gastos $gastos)
    {
        $gastos = Gastos::find($gastos->gasto_id);
        if(!$gastos){
            $data = [
                'status' => 404,
                'message' => 'Gasto no encontrado'
            ];
            return response()->json($data, 404);    
        }
        $data = [
            'status' => 200,
            'gasto' => $gastos
        ];
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gastos $gastos)
    {
        $gastos = Gastos::find($gastos->gasto_id);
        if(!$gastos){
            $data = [
                'status' => 404,
                'message' => 'Gasto no encontrado'
            ];
            return response()->json($data, 404);    
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

        $gastos->update($request->only([
            'tipo',
            'monto',
            'descripcion',
            'fecha_registro',
            'concepto_egreso_id'
        ]));

        return response()->json([
            'status' => 200,
            'message' => 'Gasto actualizado exitosamente',
            'gasto' => $gastos
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gastos $gastos)
    {
        $gastos = Gastos::find($gastos->gasto_id);
        if(!$gastos){
            $data = [
                'status' => 404,
                'message' => 'Gasto no encontrado'
            ];
            return response()->json($data, 404);    
        }
        $gastos->delete();
        $data = [
            'status' => 200,
            'message' => 'Gasto eliminado exitosamente'
        ];
        return response()->json($data, 200);
    }
}
