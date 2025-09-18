<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Egreso;
use App\Models\ConceptoEgreso;
use App\Models\ProyeccionEgreso;
use Carbon\Carbon;


class EgresoController extends Controller
{
    public function index()
    {
        // ========================
        // Traer ingresos reales
        // ========================
        $egresos = Egreso::with('conceptoEgreso')
            ->get()
            ->map(function ($egreso) {
                return [
                    'id'          => $egreso->egreso_id,
                    'concepto'    => $egreso->conceptoEgreso->nombre ?? 'Sin concepto',
                    'monto'       => $egreso->monto,
                    'tipo'        => $egreso->tipo ?? 'Egreso', // ahora existe en la tabla
                    'fecha'       => $egreso->fecha_registro,
                    'estado'      => 'Activo', // la tabla ingreso no tiene estado
                    'descripcion' => $egreso->descripcion ?? '',
                    'concepto_id' => $egreso->concepto_egreso_id,
                ];
            });

        // ========================
        // Traer proyecciones
        // ========================
        $proyecciones = ProyeccionEgreso::with('conceptoEgreso')
            ->get()
            ->map(function ($proyeccion) {
                return [
                    'id'          => $proyeccion->proyeccion_egreso_id,
                    'concepto'    => $proyeccion->conceptoEgreso->nombre ?? 'Sin concepto',
                    'monto'       => $proyeccion->monto_programado,
                    'tipo'        => 'Proyección',
                    'fecha'       => $proyeccion->fecha_inicio,
                    'fecha_fin'   => $proyeccion->fecha_fin ? $proyeccion->fecha_fin->format('Y-m-d') : '',
                    'estado'      => $proyeccion->activo ? 'Activo' : 'Inactivo',
                    'descripcion' => $proyeccion->descripcion ?? '',
                    'concepto_id' => $proyeccion->concepto_egreso_id,
                ];
            });

        // ========================
        // Fusionar ingresos y proyecciones
        // ========================
        $registros = $egresos->merge($proyecciones);

        // ========================
        // Calcular totales
        // ========================
        $totalEgresos = Egreso::sum('monto');
        $totalProyecciones = ProyeccionEgreso::sum('monto_programado');

        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        $egresoMes = Egreso::whereYear('fecha_registro', $anioActual)
            ->whereMonth('fecha_registro', $mesActual)
            ->sum('monto');

        // ========================
        // Traer conceptos (para modal de selección)
        // ========================
        $conceptoEgresos = ConceptoEgreso::all();

        // ========================
        // Enviar a la vista
        // ========================
        return view('egresos.egresos', compact(
            'registros',
            'totalEgresos',
            'totalProyecciones',
            'egresoMes',
            'conceptoEgresos'
        ));
    }

    public function store(Request $request, $id = null)
    {
        //dd($request->all());
        if ($request->isMethod('get')) {
            // Mostrar formulario con el concepto precargado si hay id
            $concepto = null;
            if ($id) {
                $concepto = ConceptoEgreso::find($id);
            }
            return view('egresos.partials.income-modal', compact('concepto'));
        }

        $tipo = $request->input('tipo');

        if ($tipo === 'Egreso') {
            $validated = $request->validate([
                'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
                'monto'               => 'required|numeric',
                'fecha'               => 'required|date',
                'descripcion'         => 'nullable|string|max:200',
            ]);

            Egreso::create([
                'tipo'                => $tipo,
                'concepto_egreso_id' => $validated['concepto_egreso_id'],
                'monto'               => $validated['monto'],
                'fecha_registro'      => $validated['fecha'],
                'descripcion'         => $validated['descripcion'] ?? '',
            ]);

            return redirect()->route('egresos.index')->with('success', 'Ingreso creado correctamente.');
        }

        if ($tipo === 'Proyección') {
            $validated = $request->validate([
                'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
                'monto'               => 'required|numeric',
                'fecha'               => 'required|date',
                'fecha_fin'           => 'required|date|after_or_equal:fecha',
                'activo'              => 'required|in:1,0',
                'descripcion'         => 'required|string|max:200',
            ]);

            ProyeccionEgreso::create([
                'monto_programado'    => $validated['monto'],
                'descripcion'         => $validated['descripcion'],
                'fecha_inicio'        => $validated['fecha'],
                'fecha_fin'           => $validated['fecha_fin'],
                'activo'              => $validated['activo'],
                'concepto_egreso_id' => $validated['concepto_egreso_id'],
            ]);

            return redirect()->route('egresos.index')->with('success', 'Proyección creada correctamente.');
        }

        return redirect()->route('egresos.index')->with('error', 'Tipo inválido.');
    }

    // private function calcularDiaRecurrencia(Carbon $fecha, string $frecuencia): ?int
    // {
    //     switch ($frecuencia) {
    //         case 'mensual':
    //         case 'trimestral':
    //         case 'semestral':
    //         case 'anual':
    //             return (int) $fecha->day;
    //         default:
    //             return null;
    //     }
    // }

    // private function calcularFechaFin(Carbon $fecha, string $frecuencia): ?string
    // {
    //     switch ($frecuencia) {
    //         case 'mensual':     return $fecha->copy()->addYear()->toDateString();
    //         case 'trimestral':  return $fecha->copy()->addYears(2)->toDateString();
    //         case 'semestral':   return $fecha->copy()->addYears(3)->toDateString();
    //         case 'anual':       return $fecha->copy()->addYears(5)->toDateString();
    //         default:            return null;
    //     }
    // }

    public function update(Request $request, $id)
    {
        $tipo = $request->input('tipo');

        if ($tipo === 'Egreso') {
            $validated = $request->validate([
                'concepto_egreso_id' => 'required|integer|exists:concepto_egreso,concepto_egreso_id',
                'monto'               => 'required|numeric',
                'fecha'               => 'required|date',
                'descripcion'         => 'nullable|string|max:200',
            ]);

            $egreso = Egreso::findOrFail($id);
            $egreso->update([
                'tipo'                => $tipo,
                'concepto_egreso_id' => $validated['concepto_egreso_id'],
                'monto'               => $validated['monto'],
                'fecha_registro'      => $validated['fecha'],
                'descripcion'         => $validated['descripcion'] ?? '',
            ]);

            return redirect()->route('egresos.index')->with('success', 'Ingreso actualizado correctamente.');
        }

        return redirect()->route('egresos.index')->with('error', 'Solo se puede editar ingresos reales desde este formulario.');
    }

    public function destroy($id)
    {
        $egreso = Egreso::findOrFail($id);
        $egreso->delete();

        return redirect()->route('egresos.index')->with('success', 'Ingreso eliminado correctamente.');
    }
}
