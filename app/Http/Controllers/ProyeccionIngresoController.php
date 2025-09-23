<?php

namespace App\Http\Controllers;

use App\Models\ProyeccionIngreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProyeccionIngresoController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $proyecciones = ProyeccionIngreso::with('conceptoIngreso')
            ->where('usuario_id', $userId)
            ->get();

        if ($proyecciones->isEmpty()) {
            return response()->json([
                'message' => 'No hay proyecciones de ingresos registradas',
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
            'concepto_ingreso_id' => 'required|integer|exists:concepto_ingreso,concepto_ingreso_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $proyeccion = ProyeccionIngreso::create([
            'monto_programado' => $request->monto_programado,
            'descripcion' => $request->descripcion,
            'fecha_creacion' => $request->input('fecha'),
            'fecha_fin' => $request->fecha_fin,
            'activo' => $request->activo,
            'concepto_ingreso_id' => $request->concepto_ingreso_id,
            'usuario_id' => $userId,
        ]);

        if (! $proyeccion) {
            return response()->json([
                'message' => 'Error al crear la proyección de ingreso',
                'status' => 500,
            ], 500);
        }

        if ($request->has('original_id')) {
            $original = ProyeccionIngreso::find($request->original_id);
            if ($original) {
                $original->fecha_fin = $request->fecha_fin;
                $original->save();
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Proyección de ingreso creada exitosamente',
                'proyeccion' => $proyeccion,
                'status' => 201,
            ], 201);
        }

        return redirect()->route('ingresos.index')->with('success', 'Proyección creada correctamente.');
    }

    public function show($id)
    {
        $userId = Auth::id();
        $proyeccion = ProyeccionIngreso::with('conceptoIngreso')
            ->where('usuario_id', $userId)
            ->find($id);

        if (! $proyeccion) {
            return response()->json([
                'message' => 'Proyección de ingreso no encontrada',
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
        $proyeccion = ProyeccionIngreso::where('usuario_id', $userId)->find($id);

        if (! $proyeccion) {
            return redirect()->route('ingresos.index')->with('error', 'Proyección no encontrada.');
        }

        $proyeccion->delete();

        return redirect()->route('ingresos.index')->with('success', 'Proyección eliminada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $validated = $request->validate([
            'concepto_ingreso_id' => 'required|integer|exists:concepto_ingreso,concepto_ingreso_id',
            'monto' => 'required|numeric',
            'fecha' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha',
            'activo' => 'required|in:1,0',
            'descripcion' => 'required|string|max:200',
        ]);

        $proyeccion = ProyeccionIngreso::where('usuario_id', $userId)->findOrFail($id);

        $proyeccion->update([
            'monto_programado' => $validated['monto'],
            'descripcion' => $validated['descripcion'],
            'fecha_creacion' => $validated['fecha'],
            'fecha_fin' => $validated['fecha_fin'],
            'activo' => $validated['activo'] == "1" ? true : false,
            'concepto_ingreso_id' => $validated['concepto_ingreso_id'],
        ]);

        return redirect()->route('ingresos.index')->with('success', 'Proyección actualizada correctamente.');
    }

    public function updatePartial(Request $request, $id)
    {
        $userId = Auth::id();
        $proyeccion = ProyeccionIngreso::where('usuario_id', $userId)->find($id);

        if (! $proyeccion) {
            return response()->json([
                'message' => 'Proyección de ingreso no encontrada',
                'status' => 404,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'monto_programado' => 'nullable|numeric',
            'descripcion' => 'nullable|string|max:200',
            'fecha_inicio' => 'nullable|date',
            'activo' => 'nullable|boolean',
            'fecha_creacion' => 'nullable|date',
            'concepto_ingreso_id' => 'nullable|integer',
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
        if ($request->has('concepto_ingreso_id')) {
            $proyeccion->concepto_ingreso_id = $request->concepto_ingreso_id;
        }

        $proyeccion->save();

        return response()->json([
            'message' => 'Proyección de ingreso actualizada parcialmente exitosamente',
            'proyeccion' => $proyeccion,
            'status' => 200,
        ], 200);
    }

    public function proyeccionesParaConfirmar()
    {
        $userId = Auth::id();
        $hoy = now()->toDateString();

        $proyecciones = ProyeccionIngreso::where('usuario_id', $userId)
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
            $proy = ProyeccionIngreso::where('usuario_id', $userId)->find($id);
            if ($proy && $proy->activo) {
                // Registrar ingreso real
                \App\Models\Ingreso::create([
                    'concepto_ingreso_id' => $proy->concepto_ingreso_id,
                    'monto' => $proy->monto_programado,
                    'fecha_registro' => $hoy,
                    'descripcion' => $proy->descripcion,
                    'tipo' => 'Ingreso',
                    'usuario_id' => $userId,
                ]);
                // Actualizar ultima_generacion
                $proy->ultima_generacion = $hoy;
                $proy->save();
            }
        }

        return response()->json(['message' => 'Ingresos recurrentes registrados']);
    }

    public function proyeccionesRecordatorioHoy()
    {
        $hoy = now()->toDateString();
        $userId = Auth::id();

        $proyecciones = ProyeccionIngreso::with('conceptoIngreso')
            ->where('usuario_id', $userId)
            ->whereDate('fecha_fin', $hoy)
            ->where('activo', 1)
            ->get();

        return response()->json([
            'proyecciones' => $proyecciones
        ]);
    }
}
