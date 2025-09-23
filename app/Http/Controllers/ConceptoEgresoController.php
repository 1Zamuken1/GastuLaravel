<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConceptoEgreso;
use Illuminate\Support\Facades\Validator;
use App\Models\ProyeccionEgreso;

class ConceptoEgresoController extends Controller
{
    public function index(){
        $conceptosEgreso = ConceptoEgreso::all();

        if($conceptosEgreso->isEmpty()){
            $data = [
                'message' => 'No hay conceptos de egreso registrados',
                'status' => 200
            ];
            return response()->json($conceptosEgreso, 404);
        }

        $data=[
            'conceptosEgreso' => $conceptosEgreso,
            'status' => 200
        ];

        return response()->json($conceptosEgreso, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
            'usuario_id' => 'nullable|integer',
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $conceptoEgreso = ConceptoEgreso::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'usuario_id' => $request->usuario_id
        ]);

        if(!$conceptoEgreso){
            $data = [
                'message' => 'Error al crear el concepto de egreso',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Concepto de egreso creado exitosamente',
            'conceptoEgreso' => $conceptoEgreso,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show($id){
        $conceptoEgreso = ConceptoEgreso::find($id);

        if(!$conceptoEgreso){
            $data = [
                'message' => 'Concepto de egreso no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'conceptoEgreso' => $conceptoEgreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id){
        $conceptoEgreso = ConceptoEgreso::find($id);

        if(!$conceptoEgreso){
            $data = [
                'message' => 'Concepto de egreso no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $conceptoEgreso->delete();

        $data = [
            'message' => 'Concepto de egreso eliminado exitosamente',
            'conceptoEgreso' => $conceptoEgreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $conceptoEgreso = ConceptoEgreso::find($id);

        if(!$conceptoEgreso){
            $data = [
                'message' => 'Concepto de egreso no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
            'usuario_id' => 'nullable|integer',
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $conceptoEgreso->nombre = $request->nombre;
        $conceptoEgreso->descripcion = $request->descripcion;
        $conceptoEgreso->save();

        $data = [
            'message' => 'Concepto de egreso actualizado exitosamente',
            'conceptoEgreso' => $conceptoEgreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id){
        $conceptoEgreso = ConceptoEgreso::find($id);

        if(!$conceptoEgreso){
            $data = [
                'message' => 'Concepto de egreso no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string|max:200',
            'usuario_id' => 'nullable|integer',
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if($request->has('nombre')){
            $conceptoEgreso->nombre = $request->nombre;
        }
        if($request->has('descripcion')){
            $conceptoEgreso->descripcion = $request->descripcion;
        }
        if($request->has('usuario_id')){
           $conceptoEgreso->usuario_id = $request->usuario_id;
        }

        $conceptoEgreso->save();

        $data = [
            'message' => 'Concepto de egreso actualizado parcialmente exitosamente',
            'conceptoEgreso' => $conceptoEgreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}