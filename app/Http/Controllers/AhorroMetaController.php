<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AhorroMeta;
use Illuminate\Support\Facades\Validator; // valida las peticiones

class AhorroMetaController extends Controller
{
    //si no hay ningun ahorro registrado->index
    public function index(){
        $ahorroMeta = AhorroMeta::all();

        if($ahorroMeta->isEmpty()){
            $data = [
                'message' => 'No hay ahorros registrados',
                "status" => 200
            ];
            return response()->json($data, 200);
        }
        $data=[
            'ahorroMeta'=> $ahorroMeta,
            'status'=>200
        ];
        return response()->json($data,200);
    }


    //crear registro de ahorro->store
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'concepto' => 'required|string|max:60',
            'descripcion' => 'nullable|string|max:200',
            'monto_meta' => 'required|numeric',
            'total_acumulado' => 'nullable|numeric',
            'fecha_creacion' => 'required|date',
            'fecha_meta' => 'required|date',
            'activa' => 'required|boolean',
            'usuario_id' => 'required|integer'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        // crea el ahorro con los datos validados
        $ahorroMeta = AhorroMeta::create([
            'concepto' => $request->concepto,
            'descripcion' => $request->descripcion,
            'monto_meta' => $request->monto_meta,
            'total_acumulado' => $request->total_acumulado ?? 0, // el total acumulado emppieza en 0 si no se proporciona
            'fecha_creacion' => $request->fecha_creacion,
            'fecha_meta' => $request->fecha_meta,
            'activa' => $request->activa,
            'usuario_id' => $request->usuario_id
        ]);

        if (!$ahorroMeta) {
            $data = [
                'message' => 'Error al crear el ahorro',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Ahorro registrado exitosamente',
            'ahorroMeta' => $ahorroMeta,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    //mostrar un solo ahorro en especifico por su id->show
    public function show($id){
        $ahorroMeta = AhorroMeta::find($id);

        if(!$ahorroMeta){
            $data = [
                'message' => 'Ahorro no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'ahorroMeta' => $ahorroMeta,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    //actualizar todos los registros de un ahorro-> update
    public function update(Request $request, $id){
        $ahorroMeta = AhorroMeta::find($id);

        if(!$ahorroMeta){
            $data = [
                'message' => 'Ahorro no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(),[
            'concepto' => 'required|string|max:60',
            'descripcion' => 'nullable|string|max:200',
            'monto_meta' => 'required|numeric',
            'total_acumulado' => 'nullable|numeric',
            'fecha_creacion' => 'required|date',
            'fecha_meta' => 'required|date',
            'activa' => 'required|boolean',
            'usuario_id' => 'required|integer'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
// se actualizan TODOS los campos del ahorro
        $ahorroMeta->concepto = $request->concepto;
        $ahorroMeta->descripcion = $request->descripcion;
        $ahorroMeta->monto_meta = $request->monto_meta;
        $ahorroMeta->total_acumulado = $request->total_acumulado;
        $ahorroMeta->fecha_creacion = $request->fecha_creacion;
        $ahorroMeta->fecha_meta = $request->fecha_meta;
        $ahorroMeta->activa = $request->activa;
        $ahorroMeta->usuario_id = $request->usuario_id;

        $ahorroMeta->save(); //guarda los cambios en la base de datos

        $data = [
            'message' => 'Ahorro actualizado exitosamente',
            'ahorroMeta' => $ahorroMeta,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    //actualizar solo un campo del registro de un ahorro-> updatePartial
    public function updatePartial(Request $request, $id){
        $ahorroMeta = AhorroMeta::find($id);

        if(!$ahorroMeta){
            $data = [
                'message' => 'Ahorro no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(),[
            'concepto' => 'sometimes|string|max:60', //sometimes solo actualiza el que mande, se utiliza en path para los required
            'descripcion' => 'sometimes|nullable|string|max:200',
            'monto_meta' => 'sometimes|numeric',
            'total_acumulado' => 'sometimes|nullable|numeric',
            'fecha_creacion' => 'sometimes|date',
            'fecha_meta' => 'sometimes|date',
            'activa' => 'sometimes|boolean',
            'usuario_id' => 'sometimes|integer'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
// se actualizan UNICAMENTE los campos que se envian en la peticion, por eso es un if anidado en cada campo
        if($request->has('concepto')){
            $ahorroMeta->concepto = $request->concepto;
        }
        if($request->has('descripcion')){
            $ahorroMeta->descripcion = $request->descripcion;
        }
        if($request->has('monto_meta')){
            $ahorroMeta->monto_meta = $request->monto_meta;
        }
        if($request->has('total_acumulado')){
            $ahorroMeta->total_acumulado = $request->total_acumulado;
        }
        if($request->has('fecha_creacion')){
            $ahorroMeta->fecha_creacion = $request->fecha_creacion;
        }
        if($request->has('fecha_meta')){
            $ahorroMeta->fecha_meta = $request->fecha_meta;
        }
        if($request->has('activa')){
            $ahorroMeta->activa = $request->activa;
        }
        if($request->has('usuario_id')){
            $ahorroMeta->usuario_id = $request->usuario_id;
        }

        $ahorroMeta->save();

        $data = [
            'message' => 'Ahorro actualizado exitosamente',
            'ahorroMeta' => $ahorroMeta,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    
    //eliminar un registro de ahorro-> destroy
    public function destroy($id){
        $ahorroMeta = AhorroMeta::find($id);

        if(!$ahorroMeta){
            $data = [
                'message' => 'Ahorro no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $ahorroMeta->delete();

        $data = [
            'message' => 'Ahorro eliminado exitosamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    
}
