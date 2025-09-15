<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProyeccionIngreso;
use Illuminate\Support\Facades\Validator;
use App\Models\ConceptoIngreso;

class ProyeccionIngresoController extends Controller
{
    public function index(){
        $proyecciones = ProyeccionIngreso::all();

        if($proyecciones->isEmpty()){
            $data = [
                'message' => 'No hay proyecciones de ingresos registradas',
                'status' => 200
            ];
            return response()->json($data, 404);
        }

        $data=[
            'proyecciones' => $proyecciones,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'monto_programado'    => 'required|numeric',
            'descripcion'         => 'required|string|max:200',
            'frecuencia'          => 'required|string|max:30',
            'dia_recurrencia'     => 'nullable|integer',
            'fecha_inicio'        => 'required|date',
            'fecha_fin'           => 'nullable|date',
            'activo'              => 'required|boolean',
            'fecha_creacion'      => 'nullable|date',
            'ultima_generacion'   => 'nullable|date',
            'concepto_ingreso_id' => 'required|integer',
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $proyeccion = ProyeccionIngreso::create([
            'monto_programado'    => $request->monto_programado,
            'descripcion'         => $request->descripcion,
            'frecuencia'          => $request->frecuencia,
            'dia_recurrencia'     => $request->dia_recurrencia,
            'fecha_inicio'        => $request->fecha_inicio,
            'fecha_fin'           => $request->fecha_fin,
            'activo'              => $request->activo,
            'fecha_creacion'      => $request->fecha_creacion,
            'ultima_generacion'   => $request->ultima_generacion,
            'concepto_ingreso_id' => $request->concepto_ingreso_id,
        ]);

        if(!$proyeccion){
            $data = [
                'message' => 'Error al crear la proyección de ingreso',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Proyección de ingreso creada exitosamente',
            'proyeccion' => $proyeccion,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    function show($id){
        $proyeccion = ProyeccionIngreso::find($id);

        if(!$proyeccion){
            $data = [
                'message' => 'Proyección de ingreso no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'proyeccion' => $proyeccion,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id){
        $proyeccion = ProyeccionIngreso::find($id);

        if(!$proyeccion){
            return redirect()->route('ingresos.index')->with('error', 'Proyección no encontrada.');
        }

        $proyeccion->delete();

        return redirect()->route('ingresos.index')->with('success', 'Proyección eliminada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $proyeccion = ProyeccionIngreso::find($id);

        if (!$proyeccion) {
            return redirect()->route('ingresos.index')->with('error', 'Proyección no encontrada.');
        }

        // Mapeo de campos del formulario a los de la tabla
        $data = [
            'monto_programado'    => $request->input('monto'),
            'descripcion'         => $request->input('descripcion'),
            'frecuencia'          => $request->input('frecuencia'),
            'fecha_inicio'        => $request->input('fecha'),
            'activo'              => $request->input('estado') === 'Activo' ? 1 : 0,
            'concepto_ingreso_id' => $request->input('concepto_ingreso_id'),
        ];

        $validated = Validator::make($data, [
            'monto_programado'    => 'required|numeric',
            'descripcion'         => 'required|string|max:200',
            'frecuencia'          => 'required|in:ninguna,diaria,semanal,quincenal,mensual,trimestral,semestral,anual',
            'fecha_inicio'        => 'required|date',
            'activo'              => 'required|in:0,1',
            'concepto_ingreso_id' => 'required|integer|exists:concepto_ingreso,concepto_ingreso_id',
        ])->validate();

        $proyeccion->update($validated);

        return redirect()->route('ingresos.index')->with('success', 'Proyección actualizada correctamente.');
    }

    public function updatePartial(Request $request, $id){
        $proyeccion = ProyeccionIngreso::find($id);

        if(!$proyeccion){
            $data = [
                'message' => 'Proyección de ingreso no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'monto_programado'    => 'nullable|numeric',
            'descripcion'         => 'nullable|string|max:200',
            'frecuencia'          => 'nullable|string|max:30',
            'dia_recurrencia'     => 'nullable|integer',
            'fecha_inicio'        => 'nullable|date',
            'fecha_fin'           => 'nullable|date',
            'activo'              => 'nullable|boolean',
            'fecha_creacion'      => 'nullable|date',
            'ultima_generacion'   => 'nullable|date',
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

        if($request->has('monto_programado')){
            $proyeccion->monto_programado = $request->monto_programado;
        }
        if($request->has('descripcion')){
            $proyeccion->descripcion = $request->descripcion;
        }
        if($request->has('frecuencia')){
            $proyeccion->frecuencia = $request->frecuencia;
        }
        if($request->has('dia_recurrencia')){
            $proyeccion->dia_recurrencia = $request->dia_recurrencia;
        }
        if($request->has('fecha_inicio')){
            $proyeccion->fecha_inicio = $request->fecha_inicio;
        }
        if($request->has('fecha_fin')){
            $proyeccion->fecha_fin = $request->fecha_fin;
        }
        if($request->has('activo')){
            $proyeccion->activo = $request->activo;
        }
        if($request->has('fecha_creacion')){
            $proyeccion->fecha_creacion = $request->fecha_creacion;
        }
        if($request->has('ultima_generacion')){
            $proyeccion->ultima_generacion = $request->ultima_generacion;
        }
        if($request->has('concepto_ingreso_id')){
            $proyeccion->concepto_ingreso_id = $request->concepto_ingreso_id;
        }

        $proyeccion->save();

        $data = [
            'message' => 'Proyección de ingreso actualizada parcialmente exitosamente',
            'proyeccion' => $proyeccion,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function proyeccionesParaConfirmar()
    {
        $hoy = now()->toDateString();

        $proyecciones = ProyeccionIngreso::where('activo', 1)
            ->where('fecha_inicio', '<=', $hoy)
            ->where(function ($q) use ($hoy) {
                $q->whereNull('ultima_generacion')
                  ->orWhere('ultima_generacion', '<', $hoy);
            })
            ->get();

        return response()->json(['proyecciones' => $proyecciones]);
    }

    public function confirmarRecurrencias(Request $request)
{
    $ids = $request->input('ids', []);
    $hoy = now()->toDateString();

    foreach ($ids as $id) {
        $proy = ProyeccionIngreso::find($id);
        if ($proy && $proy->activo) {
            // Registrar ingreso real
            \App\Models\Ingreso::create([
                'concepto_ingreso_id' => $proy->concepto_ingreso_id,
                'monto'               => $proy->monto_programado,
                'fecha_registro'      => $hoy,
                'descripcion'         => $proy->descripcion,
                'tipo'                => 'Ingreso',
            ]);
            // Actualizar ultima_generacion
            $proy->ultima_generacion = $hoy;
            $proy->save();
        }
    }

    return response()->json(['message' => 'Ingresos recurrentes registrados']);
}
}
