<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Egreso;
use App\Models\ConceptoEgreso;
use App\Models\ProyeccionEgreso;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EgresoController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        // Traer egresos reales
        $egresos = Egreso::with('conceptoEgreso')
            ->where('usuario_id', $userId)
            ->get()
            ->map(function ($egreso) {
                return [
                    'id'          => $egreso->egreso_id,
                    'concepto'    => $egreso->conceptoEgreso ? $egreso->conceptoEgreso->nombre : 'Sin concepto',
                    'monto'       => $egreso->monto,
                    'tipo'        => $egreso->tipo ?? 'Egreso',
                    'fecha'       => $egreso->fecha_registro,
                    'estado'      => 'Activo', // no existe campo estado en egresos
                    'descripcion' => $egreso->descripcion ?? '',
                    'concepto_id' => $egreso->concepto_egreso_id,
                ];
            });

        // Traer proyecciones
        $proyecciones = ProyeccionEgreso::with('conceptoEgreso')
            ->where('usuario_id', $userId)
            ->get()
            ->map(function ($proyeccion) {
                return [
                    'id'          => $proyeccion->proyeccion_egreso_id,
                    'concepto'    => $proyeccion->conceptoEgreso ? $proyeccion->conceptoEgreso->nombre : 'Sin concepto',
                    'monto'       => $proyeccion->monto_programado,
                    'tipo'        => 'Proyección',
                    'fecha'       => $proyeccion->fecha_creacion,
                    'fecha_fin'   => $proyeccion->fecha_fin ? $proyeccion->fecha_fin->format('Y-m-d') : '',
                    'estado'      => $proyeccion->activo ? 'Activo' : 'Inactivo',
                    'descripcion' => $proyeccion->descripcion ?? '',
                    'concepto_id' => $proyeccion->concepto_egreso_id,
                ];
            });

        // Fusionar egresos y proyecciones
        $registros = $egresos->concat($proyecciones)->values();

        // ========================
        // Calcular totales solo del usuario logueado
        // ========================
        $totalEgresos = Egreso::where('usuario_id', $userId)->sum('monto');
        $totalProyecciones = ProyeccionEgreso::where('usuario_id', $userId)->sum('monto_programado');

        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        $egresosMes = Egreso::where('usuario_id', $userId)
            ->whereYear('fecha_registro', $anioActual)
            ->whereMonth('fecha_registro', $mesActual)
            ->sum('monto');

        // Traer conceptos (para modal de selección)
        $conceptoEgresos = ConceptoEgreso::all();

        return view('egresos.egresos', compact(
            'registros',
            'totalEgresos',
            'totalProyecciones',
            'egresosMes',
            'conceptoEgresos'
        ));
    }

    public function store(Request $request, $id = null)
    {
        $userId = Auth::id();

        if ($request->isMethod('get')) {
            $concepto = null;
            if ($id) {
                $concepto = ConceptoEgreso::find($id);
            }
            return view('egresos.partials.expense-modal', compact('concepto'));
        }

        $tipo = $request->input('tipo');

        if ($tipo === 'Egreso') {
            $validated = $request->validate([
                'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
                'monto'              => 'required|numeric',
                'fecha'              => 'required|date',
                'descripcion'        => 'nullable|string|max:200',
            ]);

            Egreso::create([
                'tipo'               => $tipo,
                'concepto_egreso_id' => $validated['concepto_egreso_id'],
                'monto'              => $validated['monto'],
                'fecha_registro'     => $validated['fecha'],
                'descripcion'        => $validated['descripcion'] ?? '',
                'usuario_id'         => $userId,
            ]);

            return redirect()->route('egresos.index')->with('success', 'Egreso creado correctamente.');
        }

        if ($tipo === 'Proyección') {
            $validated = $request->validate([
                'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
                'monto'              => 'required|numeric',
                'fecha'              => 'required|date',
                'fecha_fin'          => 'required|date|after_or_equal:fecha',
                'activo'             => 'required|in:1,0',
                'descripcion'        => 'required|string|max:200',
            ]);

            ProyeccionEgreso::create([
                'monto_programado'   => $validated['monto'],
                'descripcion'        => $validated['descripcion'],
                'fecha_creacion'     => $validated['fecha'],
                'fecha_fin'          => $validated['fecha_fin'],
                'activo'             => $validated['activo'],
                'concepto_egreso_id' => $validated['concepto_egreso_id'],
                'usuario_id'         => $userId,
            ]);

            return redirect()->route('egresos.index')->with('success', 'Proyección creada correctamente.');
        }

        return redirect()->route('egresos.index')->with('error', 'Tipo inválido.');
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        $tipo = $request->input('tipo');

        if ($tipo === 'Egreso') {
            $egreso = Egreso::where('usuario_id', $userId)->findOrFail($id);

            $validated = $request->validate([
                'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
                'monto'              => 'required|numeric',
                'fecha'              => 'required|date',
                'descripcion'        => 'nullable|string|max:200',
            ]);

            $egreso->update([
                'tipo'               => $tipo,
                'concepto_egreso_id' => $validated['concepto_egreso_id'],
                'monto'              => $validated['monto'],
                'fecha_registro'     => $validated['fecha'],
                'descripcion'        => $validated['descripcion'] ?? '',
            ]);

            return redirect()->route('egresos.index')->with('success', 'Egreso actualizado correctamente.');
        }

        if ($tipo === 'Proyección') {
            $proyeccion = ProyeccionEgreso::where('usuario_id', $userId)->findOrFail($id);

            $validated = $request->validate([
                'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
                'monto'              => 'required|numeric',
                'fecha'              => 'required|date',
                'fecha_fin'          => 'required|date|after_or_equal:fecha',
                'activo'             => 'required|in:1,0',
                'descripcion'        => 'required|string|max:200',
            ]);

            $proyeccion->update([
                'monto_programado'   => $validated['monto'],
                'descripcion'        => $validated['descripcion'],
                'fecha_creacion'     => $validated['fecha'],
                'fecha_fin'          => $validated['fecha_fin'],
                'activo'             => $validated['activo'],
                'concepto_egreso_id' => $validated['concepto_egreso_id'],
            ]);

            return redirect()->route('egresos.index')->with('success', 'Proyección actualizada correctamente.');
        }

        return redirect()->route('egresos.index')->with('error', 'Solo se puede editar egresos reales desde este formulario.');
    }

    public function destroy($id)
    {
        $userId = Auth::id();

        $egreso = Egreso::where('usuario_id', $userId)->findOrFail($id);
        $egreso->delete();

        return redirect()->route('egresos.index')->with('success', 'Egreso eliminado correctamente.');
    }
}