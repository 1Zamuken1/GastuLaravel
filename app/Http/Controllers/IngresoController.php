<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingreso;
use App\Models\ConceptoIngreso;
use App\Models\ProyeccionIngreso;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IngresoController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Traer ingresos reales
        $ingresos = Ingreso::with('conceptoIngreso')
            ->where('usuario_id', $userId)
            ->get()
            ->map(function ($ingreso) {
                return [
                    'id'          => $ingreso->ingreso_id,
                    'concepto'    => $ingreso->conceptoIngreso ? $ingreso->conceptoIngreso->nombre : 'Sin concepto',
                    'monto'       => $ingreso->monto,
                    'tipo'        => $ingreso->tipo ?? 'Ingreso',
                    'tipo'        => $ingreso->tipo ?? 'Ingreso',
                    'fecha'       => $ingreso->fecha_registro,
                    'estado'      => 'Activo', // no existe campo estado en ingresos
                    'descripcion' => $ingreso->descripcion ?? '',
                    'concepto_id' => $ingreso->concepto_ingreso_id,
                ];
            });

        // Traer proyecciones
        $proyecciones = ProyeccionIngreso::with('conceptoIngreso')
            ->where('usuario_id', $userId)
            ->get()
            ->map(function ($proyeccion) {
                return [
                    'id'          => $proyeccion->proyeccion_ingreso_id,
                    'concepto'    => $proyeccion->conceptoIngreso ? $proyeccion->conceptoIngreso->nombre : 'Sin concepto',
                    'monto'       => $proyeccion->monto_programado,
                    'tipo'        => 'Proyección',
                    'fecha'       => $proyeccion->fecha_creacion,
                    'fecha_fin'   => $proyeccion->fecha_fin ? $proyeccion->fecha_fin->format('Y-m-d') : '',
                    'estado'      => $proyeccion->activo ? 'Activo' : 'Inactivo',
                    'descripcion' => $proyeccion->descripcion ?? '',
                    'concepto_id' => $proyeccion->concepto_ingreso_id,
                ];
            });

        // Fusionar ingresos y proyecciones
        $registros = $ingresos->concat($proyecciones)->values();

        // ========================
        // Calcular totales solo del usuario logueado
        // ========================
        $totalIngresos = Ingreso::where('usuario_id', $userId)->sum('monto');
        $totalProyecciones = ProyeccionIngreso::where('usuario_id', $userId)->sum('monto_programado');

        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        $ingresosMes = Ingreso::where('usuario_id', $userId)
            ->whereYear('fecha_registro', $anioActual)
        $ingresosMes = Ingreso::where('usuario_id', $userId)
            ->whereYear('fecha_registro', $anioActual)
            ->whereMonth('fecha_registro', $mesActual)
            ->sum('monto');

        // Traer conceptos (para modal de selección)
        $conceptoIngresos = ConceptoIngreso::all();

        return view('ingresos.ingresos', compact(
            'registros',
            'totalIngresos',
            'totalProyecciones',
            'ingresosMes',
            'conceptoIngresos'
        ));
    }

    public function store(Request $request, $id = null)
    {
        $userId = Auth::id();

        if ($request->isMethod('get')) {
            $concepto = null;
            if ($id) {
                $concepto = ConceptoIngreso::find($id);
            }
            return view('ingresos.partials.income-modal', compact('concepto'));
        }

        $tipo = $request->input('tipo');

        if ($tipo === 'Ingreso') {
            $validated = $request->validate([
                'concepto_ingreso_id' => 'required|integer|exists:concepto_ingreso,concepto_ingreso_id',
                'monto'               => 'required|numeric',
                'fecha'               => 'required|date',
                'descripcion'         => 'nullable|string|max:200',
            ]);

            Ingreso::create([
                'tipo'                => $tipo,
                'concepto_ingreso_id' => $validated['concepto_ingreso_id'],
                'monto'               => $validated['monto'],
                'fecha_registro'      => $validated['fecha'],
                'descripcion'         => $validated['descripcion'] ?? '',
                'usuario_id'          => $userId,
                'usuario_id'          => $userId,
            ]);

            return redirect()->route('ingresos.index')->with('success', 'Ingreso creado correctamente.');
        }

        if ($tipo === 'Proyección') {
            $validated = $request->validate([
                'concepto_ingreso_id' => 'required|integer|exists:concepto_ingreso,concepto_ingreso_id',
                'monto'               => 'required|numeric',
                'fecha'               => 'required|date',
                'fecha'               => 'required|date',
                'fecha_fin'           => 'required|date|after_or_equal:fecha',
                'activo'              => 'required|in:1,0',
                'descripcion'         => 'required|string|max:200',
            ]);

            ProyeccionIngreso::create([
                'monto_programado'    => $validated['monto'],
                'descripcion'         => $validated['descripcion'],
                'fecha_creacion'      => $validated['fecha'],
                'fecha_creacion'      => $validated['fecha'],
                'fecha_fin'           => $validated['fecha_fin'],
                'activo'              => $validated['activo'],
                'concepto_ingreso_id' => $validated['concepto_ingreso_id'],
                'usuario_id'          => $userId,
                'usuario_id'          => $userId,
            ]);

            return redirect()->route('ingresos.index')->with('success', 'Proyección creada correctamente.');
        }

        return redirect()->route('ingresos.index')->with('error', 'Tipo inválido.');
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        $tipo = $request->input('tipo');

        if ($tipo === 'Ingreso') {
            $ingreso = Ingreso::where('usuario_id', $userId)->findOrFail($id);

            $validated = $request->validate([
                'concepto_ingreso_id' => 'required|integer|exists:concepto_ingreso,concepto_ingreso_id',
                'monto'               => 'required|numeric',
                'fecha'               => 'required|date',
                'descripcion'         => 'nullable|string|max:200',
            ]);

            $ingreso->update([
                'tipo'                => $tipo,
                'concepto_ingreso_id' => $validated['concepto_ingreso_id'],
                'monto'               => $validated['monto'],
                'fecha_registro'      => $validated['fecha'],
                'descripcion'         => $validated['descripcion'] ?? '',
            ]);

            return redirect()->route('ingresos.index')->with('success', 'Ingreso actualizado correctamente.');
        }

        if ($tipo === 'Proyección') {
            $proyeccion = ProyeccionIngreso::where('usuario_id', $userId)->findOrFail($id);

            $validated = $request->validate([
                'concepto_ingreso_id' => 'required|integer|exists:concepto_ingreso,concepto_ingreso_id',
                'monto'               => 'required|numeric',
                'fecha'               => 'required|date',
                'fecha_fin'           => 'required|date|after_or_equal:fecha',
                'activo'              => 'required|in:1,0',
                'descripcion'         => 'required|string|max:200',
            ]);

            $proyeccion->update([
                'monto_programado'    => $validated['monto'],
                'descripcion'         => $validated['descripcion'],
                'fecha_creacion'      => $validated['fecha'],
                'fecha_creacion'      => $validated['fecha'],
                'fecha_fin'           => $validated['fecha_fin'],
                'activo'              => $validated['activo'],
                'concepto_ingreso_id' => $validated['concepto_ingreso_id'],
            ]);

            return redirect()->route('ingresos.index')->with('success', 'Proyección actualizada correctamente.');
        }

        return redirect()->route('ingresos.index')->with('error', 'Solo se puede editar ingresos reales desde este formulario.');
    }

    public function destroy($id)
    {
        $userId = Auth::id();

        $ingreso = Ingreso::where('usuario_id', $userId)->findOrFail($id);
        $ingreso->delete();

        return redirect()->route('ingresos.index')->with('success', 'Ingreso eliminado correctamente.');
    }
}
