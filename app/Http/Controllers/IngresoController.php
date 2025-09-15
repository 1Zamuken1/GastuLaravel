<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingreso;
use App\Models\ConceptoIngreso;
use App\Models\ProyeccionIngreso;
use Carbon\Carbon;

class IngresoController extends Controller
{
    public function index()
    {
        // ========================
        // Traer ingresos reales
        // ========================
        $ingresos = Ingreso::with('conceptoIngreso')
            ->get()
            ->map(function ($ingreso) {
                return [
                    'id' => $ingreso->ingreso_id,
                    'concepto' => $ingreso->conceptoIngreso->nombre ?? 'Sin concepto',
                    'monto' => $ingreso->monto,
                    'tipo' => 'Ingreso',
                    'fecha' => $ingreso->fecha_registro,
                    'estado' => 'Activo', // la tabla ingreso no tiene estado
                    'descripcion' => $ingreso->descripcion ?? '',
                    'concepto_id' => $ingreso->concepto_ingreso_id,
                ];
            });

        // ========================
        // Traer proyecciones
        // ========================
        $proyecciones = ProyeccionIngreso::with('conceptoIngreso')
            ->get()
            ->map(function ($proyeccion) {
                return [
                    'id' => $proyeccion->proyeccion_ingreso_id,
                    'concepto' => $proyeccion->conceptoIngreso->nombre ?? 'Sin concepto',
                    'monto' => $proyeccion->monto_programado,
                    'tipo' => 'Proyección',
                    'fecha' => $proyeccion->fecha_inicio,
                    'estado' => $proyeccion->activo ? 'Activo' : 'Inactivo',
                    'descripcion' => $proyeccion->descripcion ?? '', // <-- AGREGAR ESTO
            'concepto_id' => $proyeccion->concepto_ingreso_id,
                ];
            });

        // ========================
        // Fusionar ingresos y proyecciones
        // ========================
        $registros = $ingresos->merge($proyecciones);

        // ========================
        // Calcular totales
        // ========================
        $totalIngresos = Ingreso::sum('monto');
        $totalProyecciones = ProyeccionIngreso::sum('monto_programado');

        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        $ingresosMes = Ingreso::whereYear('fecha_registro', $anioActual)
            ->whereMonth('fecha_registro', $mesActual)
            ->sum('monto');

        // ========================
        // Traer conceptos (para modal de selección)
        // ========================
        $conceptoIngresos = ConceptoIngreso::all();

        // ========================
        // Enviar a la vista
        // ========================
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
    if ($request->isMethod('get')) {
        // Mostrar formulario con el concepto precargado si hay id
        $concepto = null;
        if ($id) {
            $concepto = ConceptoIngreso::find($id);
        }
        return view('ingresos.partials.income-modal', compact('concepto'));
    }

    // Aquí va tu lógica actual de guardar
    $tipo = $request->input('tipo');

    if ($tipo === 'Ingreso') {
        $validated = $request->validate([
            'concepto_ingreso_id' => 'required|integer|exists:concepto_ingreso,concepto_ingreso_id',
            'monto'               => 'required|numeric',
            'fecha'               => 'required|date',
            'descripcion'         => 'nullable|string|max:200',
        ]);

        Ingreso::create([
            'concepto_ingreso_id' => $validated['concepto_ingreso_id'],
            'monto'               => $validated['monto'],
            'fecha_registro'      => $validated['fecha'],
            'descripcion'         => $validated['descripcion'] ?? '',
        ]);

        return redirect()->route('ingresos.index')->with('success', 'Ingreso creado correctamente.');
    }

    if ($tipo === 'Proyección') {
        $validated = $request->validate([
            'concepto_ingreso_id' => 'required|integer|exists:concepto_ingreso,concepto_ingreso_id',
            'monto'               => 'required|numeric',
            'fecha'               => 'required|date',
            'estado'              => 'required|in:Activo,Inactivo',
            'frecuencia'          => 'required|in:ninguna,diaria,semanal,quincenal,mensual,trimestral,semestral,anual',
            'descripcion'         => 'required|string|max:200',
        ]);

        $fechaInicio = Carbon::parse($validated['fecha']);

        ProyeccionIngreso::create([
            'monto_programado'   => $validated['monto'],
            'descripcion'        => $validated['descripcion'],
            'frecuencia'         => $validated['frecuencia'],
            'dia_recurrencia'    => $this->calcularDiaRecurrencia($fechaInicio, $validated['frecuencia']),
            'fecha_inicio'       => $fechaInicio->toDateString(),
            'fecha_fin'          => $this->calcularFechaFin($fechaInicio, $validated['frecuencia']),
            'activo'             => $validated['estado'] === 'Activo' ? 1 : 0,
            'ultima_generacion'  => null,
            'tipo' => $tipo,
            'concepto_ingreso_id'=> $validated['concepto_ingreso_id'],
        ]);

        return redirect()->route('ingresos.index')->with('success', 'Proyección creada correctamente.');
    }

    return redirect()->route('ingresos.index')->with('error', 'Tipo inválido.');
}


private function calcularDiaRecurrencia(Carbon $fecha, string $frecuencia): ?int
{
    switch ($frecuencia) {
        case 'mensual':
        case 'trimestral':
        case 'semestral':
        case 'anual':
            return (int) $fecha->day; // día del mes
        default:
            return null;
    }
}

private function calcularFechaFin(Carbon $fecha, string $frecuencia): ?string
{
    switch ($frecuencia) {
        case 'mensual':     return $fecha->copy()->addYear()->toDateString();    // 1 año
        case 'trimestral':  return $fecha->copy()->addYears(2)->toDateString();  // 2 años
        case 'semestral':   return $fecha->copy()->addYears(3)->toDateString();  // 3 años
        case 'anual':       return $fecha->copy()->addYears(5)->toDateString();  // 5 años
        default:            return null;
    }
}

public function update(Request $request, $id)
{
    $tipo = $request->input('tipo');

    if ($tipo === 'Ingreso') {
        $validated = $request->validate([
            'concepto_ingreso_id' => 'required|integer|exists:concepto_ingreso,concepto_ingreso_id',
            'monto'               => 'required|numeric',
            'fecha'               => 'required|date',
            'descripcion'         => 'nullable|string|max:200',
        ]);

        $ingreso = Ingreso::findOrFail($id);
        $ingreso->update([
            'tipo'                => $tipo,
            'concepto_ingreso_id' => $validated['concepto_ingreso_id'],
            'monto'               => $validated['monto'],
            'fecha_registro'      => $validated['fecha'],
            'descripcion'         => $validated['descripcion'] ?? '',
        ]);

        return redirect()->route('ingresos.index')->with('success', 'Ingreso actualizado correctamente.');
    }

    return redirect()->route('ingresos.index')->with('error', 'Solo se puede editar ingresos reales desde este formulario.');
}

public function destroy($id)
{
    $ingreso = Ingreso::findOrFail($id);
    $ingreso->delete();

    return redirect()->route('ingresos.index')->with('success', 'Ingreso eliminado correctamente.');
}


}
