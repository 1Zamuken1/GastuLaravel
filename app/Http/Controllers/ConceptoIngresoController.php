<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConceptoIngreso;
use Illuminate\Support\Facades\Validator;
use App\Models\ProyeccionIngreso;

class ConceptoIngresoController extends Controller
{
    public function index(){
        $conceptosIngreso = ConceptoIngreso::all();

        if($conceptosIngreso->isEmpty()){
            $data = [
                'message' => 'No hay conceptos de ingreso registrados',
                'status' => 200
            ];
            return response()->json($conceptosIngreso, 404);
        }

        $data=[
            'conceptosIngreso' => $conceptosIngreso,
            'status' => 200
        ];

        return response()->json($conceptosIngreso, 200);
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

        $conceptoIngreso = ConceptoIngreso::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'usuario_id' => $request->usuario_id
        ]);

        if(!$conceptoIngreso){
            $data = [
                'message' => 'Error al crear el concepto de ingreso',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Concepto de ingreso creado exitosamente',
            'conceptoIngreso' => $conceptoIngreso,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show($id){
        $conceptoIngreso = ConceptoIngreso::find($id);

        if(!$conceptoIngreso){
            $data = [
                'message' => 'Concepto de ingreso no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'conceptoIngreso' => $conceptoIngreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id){
        $conceptoIngreso = ConceptoIngreso::find($id);

        if(!$conceptoIngreso){
            $data = [
                'message' => 'Concepto de ingreso no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $conceptoIngreso->delete();

        $data = [
            'message' => 'Concepto de ingreso eliminado exitosamente',
            'conceptoIngreso' => $conceptoIngreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $conceptoIngreso = ConceptoIngreso::find($id);

        if(!$conceptoIngreso){
            $data = [
                'message' => 'Concepto de ingreso no encontrado',
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

        $conceptoIngreso->nombre = $request->nombre;
        $conceptoIngreso->descripcion = $request->descripcion;
        $conceptoIngreso->save();

        $data = [
            'message' => 'Concepto de ingreso actualizado exitosamente',
            'conceptoIngreso' => $conceptoIngreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id){
        $conceptoIngreso = ConceptoIngreso::find($id);

        if(!$conceptoIngreso){
            $data = [
                'message' => 'Concepto de ingreso no encontrado',
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
            $conceptoIngreso->nombre = $request->nombre;
        }
        if($request->has('descripcion')){
            $conceptoIngreso->descripcion = $request->descripcion;
        }
        if($request->has('usuario_id')){
           $conceptoIngreso->usuario_id = $request->usuario_id;
        }

        $conceptoIngreso->save();

        $data = [
            'message' => 'Concepto de ingreso actualizado parcialmente exitosamente',
            'conceptoIngreso' => $conceptoIngreso,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
