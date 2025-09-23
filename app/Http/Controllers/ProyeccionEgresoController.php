<?php

namespace App\Http\Controllers;

use App\Models\ProyeccionEgreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProyeccionEgresoController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $proyecciones = ProyeccionEgreso::with('conceptoEgreso')
            ->where('usuario_id', $userId)
            ->get();

        if ($proyecciones->isEmpty()) {
            return response()->json([
                'message' => 'No hay proyecciones de egresos registradas',
                'status' => 200,
            ], 404);
        }

        return response()->json([
            'proyecciones' => $proyecciones,
            'status' => 200,
        ], 200);
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $validator = Validator::make($request->all(), [
            'monto_programado' => 'required|numeric',
            'descripcion' => 'required|string|max:200',
            'fecha' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha',
            'activo' => 'required|boolean',
            'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $proyeccion = ProyeccionEgreso::create([
            'monto_programado' => $request->monto_programado,
            'descripcion' => $request->descripcion,
            'fecha_creacion' => $request->input('fecha'),
            'fecha_fin' => $request->fecha_fin,
            'activo' => $request->activo,
            'concepto_egreso_id' => $request->concepto_egreso_id,
            'usuario_id' => $userId,
        ]);

        if (! $proyeccion) {
            return response()->json([
                'message' => 'Error al crear la proyección de egreso',
                'status' => 500,
            ], 500);
        }

        if ($request->has('original_id')) {
            $original = ProyeccionEgreso::find($request->original_id);
            if ($original) {
                $original->fecha_fin = $request->fecha_fin;
                $original->save();
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Proyección de egreso creada exitosamente',
                'proyeccion' => $proyeccion,
                'status' => 201,
            ], 201);
        }

        return redirect()->route('egresos.index')->with('success', 'Proyección creada correctamente.');
    }

    public function show($id)
    {
        $userId = Auth::id();
        $proyeccion = ProyeccionEgreso::with('conceptoEgreso')
            ->where('usuario_id', $userId)
            ->find($id);

        if (! $proyeccion) {
            return response()->json([
                'message' => 'Proyección de egreso no encontrada',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'proyeccion' => $proyeccion,
            'status' => 200,
        ], 200);
    }

    public function destroy($id)
    {
        $userId = Auth::id();
        $proyeccion = ProyeccionEgreso::where('usuario_id', $userId)->find($id);

        if (! $proyeccion) {
            return redirect()->route('egresos.index')->with('error', 'Proyección no encontrada.');
        }

        $proyeccion->delete();

        return redirect()->route('egresos.index')->with('success', 'Proyección eliminada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $validated = $request->validate([
            'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
            'monto' => 'required|numeric',
            'fecha' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha',
            'activo' => 'required|in:1,0',
            'descripcion' => 'required|string|max:200',
        ]);

        $proyeccion = ProyeccionEgreso::where('usuario_id', $userId)->findOrFail($id);

        $proyeccion->update([
            'monto_programado' => $validated['monto'],
            'descripcion' => $validated['descripcion'],
            'fecha_creacion' => $validated['fecha'],
            'fecha_fin' => $validated['fecha_fin'],
            'activo' => $validated['activo'] == "1" ? true : false,
            'concepto_egreso_id' => $validated['concepto_egreso_id'],
        ]);

        return redirect()->route('egresos.index')->with('success', 'Proyección actualizada correctamente.');
    }

    public function updatePartial(Request $request, $id)
    {
        $userId = Auth::id();
        $proyeccion = ProyeccionEgreso::where('usuario_id', $userId)->find($id);

        if (! $proyeccion) {
            return response()->json([
                'message' => 'Proyección de egreso no encontrada',
                'status' => 404,
            ], 404);
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
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
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

        return response()->json([
            'message' => 'Proyección de egreso actualizada parcialmente exitosamente',
            'proyeccion' => $proyeccion,
            'status' => 200,
        ], 200);
    }

    public function proyeccionesParaConfirmar()
    {
        $userId = Auth::id();
        $hoy = now()->toDateString();

        $proyecciones = ProyeccionEgreso::where('usuario_id', $userId)
            ->where('activo', 1)
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
        $userId = Auth::id();
        $ids = $request->input('ids', []);
        $hoy = now()->toDateString();

        foreach ($ids as $id) {
            $proy = ProyeccionEgreso::where('usuario_id', $userId)->find($id);
            if ($proy && $proy->activo) {
                // Registrar egreso real
                \App\Models\Egreso::create([
                    'concepto_egreso_id' => $proy->concepto_egreso_id,
                    'monto' => $proy->monto_programado,
                    'fecha_registro' => $hoy,
                    'descripcion' => $proy->descripcion,
                    'tipo' => 'Egreso',
                    'usuario_id' => $userId,
                ]);
                // Actualizar ultima_generacion
                $proy->ultima_generacion = $hoy;
                $proy->save();
            }
        }

        return response()->json(['message' => 'Egresos recurrentes registrados']);
    }

    public function proyeccionesRecordatorioHoy()
    {
        $hoy = now()->toDateString();
        $userId = Auth::id();

        $proyecciones = ProyeccionEgreso::with('conceptoEgreso')
            ->where('usuario_id', $userId)
            ->whereDate('fecha_fin', $hoy)
            ->where('activo', 1)
            ->get();

        return response()->json([
            'proyecciones' => $proyecciones
        ]);
    }
}