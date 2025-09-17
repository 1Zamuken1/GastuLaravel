<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AhorroProgramado;
use Illuminate\Support\Facades\Validator;

class AhorroProgramadoController extends Controller
{
    //si no hay ningun ahorro registrado->index
    public function index(){
        $ahorroProgramado = AhorroProgramado::all();

        if($ahorroProgramado->isEmpty()){
            $data = [
                'message' => 'No hay ahorros registrados',
                "status" => 200
            ];
            return response()->json($data, 200);
        }
        $data=[
            'ahorroProgramado'=> $ahorroProgramado,
            'status'=>200
        ];
        return response()->json($data,200);
    }

    //crear registro de ahorro->store
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'monto_programado' => 'required|numeric',
            'frecuencia' => 'required|string|max:30',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date',
            'num_cuotas' => 'nullable|integer',
            'ultimo_aporte_generado' => 'nullable|date',
            'ahorro_meta_id' => 'required|integer'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $ahorroProgramado = AhorroProgramado::create([
            'monto_programado' => $request->monto_programado,
            'frecuencia' => $request->frecuencia,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'num_cuotas' => $request->num_cuotas ?? 0,
            'ultimo_aporte_generado' => $request->ultimo_aporte_generado,
            'ahorro_meta_id' => $request->ahorro_meta_id
        ]);

        if(!$ahorroProgramado){
            $data = [
                'message' => 'Error al crear el ahorro',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Ahorro creado exitosamente',
            'ahorroProgramado' => $ahorroProgramado,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

     //mostrar un solo ahorro en especifico por su id->show
     public function show($id){
        $ahorroProgramado = AhorroProgramado::find($id);

        if(!$ahorroProgramado){
            $data = [
                'message' => 'Ahorro no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $data = [
            'ahorroProgramado' => $ahorroProgramado,
            'status' => 200
        ];
        return response()->json($data, 200);
     }

     //actualizar todos los registros de un ahorro-> update

     public function update(Request $request, $id){
        $ahorroProgramado = AhorroProgramado::find($id);

        if(!$ahorroProgramado){
            $data = [
                'message' => 'Ahorro no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(),[
            'monto_programado' => 'required|numeric',
            'frecuencia' => 'required|string|max:30',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date',
            'num_cuotas' => 'nullable|integer',
            'ultimo_aporte_generado' => 'nullable|date',
            'ahorro_meta_id' => 'required|integer'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $ahorroProgramado->monto_programado = $request->monto_programado;
        $ahorroProgramado->frecuencia = $request->frecuencia;
        $ahorroProgramado->fecha_inicio = $request->fecha_inicio;
        $ahorroProgramado->fecha_fin = $request->fecha_fin;
        $ahorroProgramado->num_cuotas = $request->num_cuotas;
        $ahorroProgramado->ultimo_aporte_generado = $request->ultimo_aporte_generado;
        $ahorroProgramado->ahorro_meta_id = $request->ahorro_meta_id;

        $ahorroProgramado->save();

        $data = [
            'message' => 'Ahorro actualizado exitosamente',
            'ahorroProgramado' => $ahorroProgramado,
            'status' => 200
        ];
        return response()->json($data, 200);
     }

     //actualizar solo un campo del registro de un ahorro-> updatePartial

     public function updatePartial(Request $request, $id){
        $ahorroProgramado = AhorroProgramado::find($id);

        if(!$ahorroProgramado){
            $data = [
                'message' => 'Ahorro no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(),[
            'monto_programado' => 'sometimes|numeric',
            'frecuencia' => 'sometimes|string|max:30',
            'fecha_inicio' => 'sometimes|date',
            'fecha_fin' => 'sometimes|nullable|date',
            'num_cuotas' => 'sometimes|nullable|integer',
            'ultimo_aporte_generado' => 'sometimes|nullable|date',
            'ahorro_meta_id' => 'sometimes|integer'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if($request->has('monto_programado')){
            $ahorroProgramado->monto_programado = $request->monto_programado;
        }
        if($request->has('frecuencia')){
            $ahorroProgramado->frecuencia = $request->frecuencia;
        }
        if($request->has('fecha_inicio')){
            $ahorroProgramado->fecha_inicio = $request->fecha_inicio;
        }
        if($request->has('fecha_fin')){
            $ahorroProgramado->fecha_fin = $request->fecha_fin;
        }
        if($request->has('num_cuotas')){
            $ahorroProgramado->num_cuotas = $request->num_cuotas;
        }
        if($request->has('ultimo_aporte_generado')){
            $ahorroProgramado->ultimo_aporte_generado = $request->ultimo_aporte_generado;
        }
        if($request->has('ahorro_meta_id')){
            $ahorroProgramado->ahorro_meta_id = $request->ahorro_meta_id;
        }

        $ahorroProgramado->save();

        $data = [
            'message' => 'Ahorro actualizado exitosamente',
            'ahorroProgramado' => $ahorroProgramado,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    //eliminar un registro de ahorro-> destroy

    public function destroy($id){
        $ahorroProgramado = AhorroProgramado::find($id);

        if(!$ahorroProgramado){
            $data = [
                'message' => 'Ahorro no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $ahorroProgramado->delete();

        $data = [
            'message' => 'Ahorro eliminado exitosamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

}
