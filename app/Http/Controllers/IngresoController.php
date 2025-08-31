<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ingreso;
use Illuminate\Support\Facades\Validator;

class ingresoController extends Controller
{
    public function index(){
        $ingresos = Ingreso::all();

        if($ingresos->isEmpty()){
            $data = [
                'message' => 'No hay ingresos registrados',
                'status' => 200
            ];
            return response()->json($ingresos, 404);
        }

        $data=[
            'ingresos' => $ingresos,
            'status' => 200
        ];

        return response()->json($ingresos, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'tipo' => 'nullable|string|max:30',
            'monto' => 'required|numeric',
            'descripcion' => 'nullable|string|max:200',
            'fecha_registro' => 'required|date',
            'concepto_ingreso_id' => 'nullable|integer',
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $ingreso = Ingreso::create([
            'tipo' => $request->tipo,
            'monto' => $request->monto,
            'descripcion' => $request->descripcion,
            'fecha_registro' => $request->fecha_registro,
            'concepto_ingreso_id' => $request->concepto_ingreso_id,
        ]);

        if(!$ingreso){
            $data = [
                'message' => 'Error al crear el ingreso',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'ingreso' => $ingreso,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show($id){
        $ingreso = Ingreso::find($id);

        if(!$ingreso){
            $data = [
                'message' => 'Ingreso no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'ingreso' => $ingreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id){
        $ingreso = Ingreso::find($id);

        if(!$ingreso){
            $data = [
                'message' => 'Ingreso no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $ingreso->delete();

        $data = [
            'message' => 'Ingreso eliminado correctamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $ingreso = Ingreso::find($id);

        if(!$ingreso){
            $data = [
                'message' => 'Ingreso no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo' => 'nullable|string|max:30',
            'monto' => 'required|numeric',
            'descripcion' => 'nullable|string|max:200',
            'fecha_registro' => 'required|date',
            'concepto_ingreso_id' => 'nullable|integer',
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $ingreso->tipo = $request->tipo;
        $ingreso->monto = $request->monto;
        $ingreso->descripcion = $request->descripcion;
        $ingreso->fecha_registro = $request->fecha_registro;
        $ingreso->concepto_ingreso_id = $request->concepto_ingreso_id;

        $ingreso->save();

        $data = [
            'ingreso' => $ingreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id){
        $ingreso = Ingreso::find($id);

        if(!$ingreso){
            $data = [
                'message' => 'Ingreso no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo' => 'nullable|string|max:30',
            'monto' => 'nullable|numeric',
            'descripcion' => 'nullable|string|max:200',
            'fecha_registro' => 'nullable|date',
            'concepto_ingreso_id' => 'nullable|integer',
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if($request->has('tipo')){
            $ingreso->tipo = $request->tipo;
        }
        if($request->has('monto')){
            $ingreso->monto = $request->monto;
        }
        if($request->has('descripcion')){
            $ingreso->descripcion = $request->descripcion;
        }
        if($request->has('fecha_registro')){
            $ingreso->fecha_registro = $request->fecha_registro;
        }
        if($request->has('concepto_ingreso_id')){
            $ingreso->concepto_ingreso_id = $request->concepto_ingreso_id;
        }

        $ingreso->save();

        $data = [
            'ingreso' => $ingreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
