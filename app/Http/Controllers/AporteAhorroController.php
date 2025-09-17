<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AporteAhorro;
use Illuminate\Support\Facades\Validator;

class AporteAhorroController extends Controller
{
    //si no hay ningun aporte registrado->index
    public function index(){
        $aporteAhorro = AporteAhorro::all();

        if($aporteAhorro->isEmpty()){
            $data = [
                'message' => 'No hay aportes registrados',
                "status" => 200
            ];
            return response()->json($data, 200);
        }
        $data=[
            'aporteAhorro'=> $aporteAhorro,
            'status'=>200
        ];
        return response()->json($data,200);
    }

    //crear registro de aporte->store
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'monto' => 'required|numeric',
            'fecha_registro' => 'required|date',
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

        $aporteAhorro = AporteAhorro::create([
            'monto' => $request->monto,
            'fecha_registro' => $request->fecha_registro,
            'ahorro_meta_id' => $request->ahorro_meta_id
        ]);

        if(!$aporteAhorro){
            $data = [
                'message' => 'Error al crear el aporte',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        //
        $this->recalcularMeta($aporteAhorro->ahorro_meta_id);

        $data = [
            'message' => 'Aporte creado exitosamente',
            'aporteAhorro' => $aporteAhorro,
            'status' => 201
        ];
        return response()->json($data, 201); 
    }

    //ver un aporte por id->show
    public function show($id){
        $aporteAhorro = AporteAhorro::find($id);

        if(!$aporteAhorro){
            $data = [
                'message' => 'Aporte no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'aporteAhorro' => $aporteAhorro,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    //actualizar un aporte por id->update
    public function update(Request $request, $id){
        $aporteAhorro = AporteAhorro::find($id);

        if(!$aporteAhorro){
            $data = [
                'message' => 'Aporte no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(),[
            'monto' => 'required|numeric',
            'fecha_registro' => 'required|date',
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

        $aporteAhorro->monto = $request->monto;
        $aporteAhorro->fecha_registro = $request->fecha_registro;
        $aporteAhorro->ahorro_meta_id = $request->ahorro_meta_id;
        $aporteAhorro->save();

        //

        $data = [
            'message' => 'Aporte actualizado exitosamente',
            'aporteAhorro' => $aporteAhorro,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    //actualizar solo un campo del registro de un aporte-> updatePartial
    public function updatePartial(Request $request, $id){
        $aporteAhorro = AporteAhorro::find($id);

        if(!$aporteAhorro){
            $data = [
                'message' => 'Aporte no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(),[
            'monto' => 'sometimes|numeric',
            'fecha_registro' => 'sometimes|date',
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

        if($request->has('monto')){
            $aporteAhorro->monto = $request->monto;
        }
        if($request->has('fecha_registro')){
            $aporteAhorro->fecha_registro = $request->fecha_registro;
        }
        if($request->has('ahorro_meta_id')){
            $aporteAhorro->ahorro_meta_id = $request->ahorro_meta_id;
        }
        $aporteAhorro->save();

        //

        $data = [
            'message' => 'Aporte actualizado exitosamente',
            'aporteAhorro' => $aporteAhorro,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    //eliminar un aporte por id->destroy
    public function destroy($id){
        $aporteAhorro = AporteAhorro::find($id);

        if(!$aporteAhorro){
            $data = [
                'message' => 'Aporte no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $aporteAhorro->delete();

        $data = [
            'message' => 'Aporte eliminado exitosamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    //
}

