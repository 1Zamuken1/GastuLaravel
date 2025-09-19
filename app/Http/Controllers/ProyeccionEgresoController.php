<?php

namespace App\Http\Controllers;

use App\Models\ProyeccionEgreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProyeccionEgresoController extends Controller
{
    public function index()
    {
        $proyecciones = ProyeccionEgreso::all();

        if ($proyecciones->isEmpty()) {
            $data = [
                'message' => 'No hay proyecciones de ingresos registradas',
                'status' => 200,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'proyecciones' => $proyecciones,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'monto_programado' => 'required|numeric',
            'descripcion' => 'required|string|max:200',
            'fecha_inicio' => 'required|date',
            'activo' => 'required|boolean',
            'fecha_creacion' => 'nullable|date',
            'concepto_egreso_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        $proyeccion = ProyeccionEgreso::create([
            'monto_programado' => $request->monto_programado,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'activo' => $request->activo,
            'fecha_creacion' => $request->fecha_creacion,
            'concepto_egreso_id' => $request->concepto_egreso_id,
        ]);

        if (! $proyeccion) {
            $data = [
                'message' => 'Error al crear la proyección de ingreso',
                'status' => 500,
            ];

            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Proyección de ingreso creada exitosamente',
            'proyeccion' => $proyeccion,
            'status' => 201,
        ];

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $proyeccion = ProyeccionEgreso::find($id);

        if (! $proyeccion) {
            $data = [
                'message' => 'Proyección de ingreso no encontrada',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'proyeccion' => $proyeccion,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $proyeccion = ProyeccionEgreso::find($id);

        if (! $proyeccion) {
            return redirect()->route('egresos.index')->with('error', 'Proyección no encontrada.');
        }

        $proyeccion->delete();

        return redirect()->route('egresos.index')->with('success', 'Proyección eliminada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
            'monto' => 'required|numeric',
            'fecha' => 'required|date',
            'activo' => 'required|in:1,0',
            'descripcion' => 'required|string|max:200',
        ]);

        $proyeccion = ProyeccionEgreso::findOrFail($id);

        $proyeccion->update([
            'monto_programado' => $validated['monto'],
            'descripcion' => $validated['descripcion'],
            'fecha_inicio' => $validated['fecha'],
            'activo' => $validated['activo'],
            'concepto_egreso_id' => $validated['concepto_egreso_id'],
        ]);

        return redirect()->route('egresos.index')->with('success', 'Proyección actualizada correctamente.');
    }

    public function updatePartial(Request $request, $id)
    {
        $proyeccion = ProyeccionEgreso::find($id);

        if (! $proyeccion) {
            $data = [
                'message' => 'Proyección de ingreso no encontrada',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'monto_programado' => 'nullable|numeric',
            'descripcion' => 'nullable|string|max:200',
            'fecha_inicio' => 'nullable|date',
            'activo' => 'nullable|boolean',
            'fecha_creacion' => 'nullable|date',
            'concepto_egreso_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        if ($request->has('monto_programado')) {
            $proyeccion->monto_programado = $request->monto_programado;
        }
        if ($request->has('descripcion')) {
            $proyeccion->descripcion = $request->descripcion;
        }
        if ($request->has('fecha_inicio')) {
            $proyeccion->fecha_inicio = $request->fecha_inicio;
        }
        if ($request->has('activo')) {
            $proyeccion->activo = $request->activo;
        }
        if ($request->has('fecha_creacion')) {
            $proyeccion->fecha_creacion = $request->fecha_creacion;
        }
        if ($request->has('concepto_egreso_id')) {
            $proyeccion->concepto_egreso_id = $request->concepto_egreso_id;
        }

        $proyeccion->save();

        $data = [
            'message' => 'Proyección de egreso actualizada parcialmente exitosamente',
            'proyeccion' => $proyeccion,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function proyeccionesParaConfirmar()
    {
        $hoy = now()->toDateString();

        $proyecciones = ProyeccionEgreso::where('activo', 1)
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
            $proy = ProyeccionEgreso::find($id);
            if ($proy && $proy->activo) {
                // Registrar ingreso real
                \App\Models\Egreso::create([
                    'concepto_egreso_id' => $proy->concepto_egreso_id,
                    'monto' => $proy->monto_programado,
                    'fecha_registro' => $hoy,
                    'descripcion' => $proy->descripcion,
                    'tipo' => 'Egreso',
                ]);
                // Actualizar ultima_generacion
                $proy->ultima_generacion = $hoy;
                $proy->save();
            }
        }

        return response()->json(['message' => 'Egresos recurrentes registrados']);
    }
}
