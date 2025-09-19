<?php

namespace App\Http\Controllers;

use App\Models\AhorroMeta;
use App\Models\AhorroProgramado;
use App\Models\AporteAhorro;
use Illuminate\Http\Request;

class AhorroMetaController extends Controller
{
    // Mostrar todos los ahorros
    public function index()
    {
        $ahorros = AhorroMeta::with('ahorroProgramados', 'aporteAhorros')->get();

        // Calcular porcentaje de avance
        $ahorros->transform(function ($ahorro) {
            $ahorro->porcentaje = $ahorro->monto_meta 
                ? round(($ahorro->total_acumulado / $ahorro->monto_meta) * 100, 2) 
                : 0;
            return $ahorro;
        });

        return view('ahorros.index', compact('ahorros'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('ahorros.create');
    }

    // Guardar un nuevo ahorro
    public function store(Request $request)
    {
        $validated = $request->validate([
            'concepto' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'monto_meta' => 'nullable|numeric|min:0',
            'fecha_meta' => 'nullable|date',
            'monto_programado' => 'required|numeric|min:1',
            'frecuencia' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date',
            'num_cuotas' => 'nullable|integer|min:1',
        ]);

        // Crear ahorro meta
        $ahorro = AhorroMeta::create([
            'concepto' => $validated['concepto'],
            'descripcion' => $validated['descripcion'] ?? null,
            'monto_meta' => $validated['monto_meta'] ?? null,
            'fecha_meta' => $validated['fecha_meta'] ?? null,
            'total_acumulado' => 0,
            'fecha_creacion' => now(),
            'activa' => 1,
            'usuario_id' => 1, // temporal mientras no hay login
        ]);

        // Crear ahorro programado
        $programado = AhorroProgramado::create([
            'ahorro_meta_id' => $ahorro->ahorro_meta_id,
            'monto_programado' => $validated['monto_programado'],
            'frecuencia' => $validated['frecuencia'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'] ?? null,
            'num_cuotas' => $validated['num_cuotas'] ?? null,
        ]);

        // Generar los aportes automáticamente
        $this->generarAportes($ahorro, $programado);

        return redirect()->route('ahorros.index')->with('success', 'Ahorro creado correctamente.');
    }

    // Función para generar aportes automáticos
    protected function generarAportes(AhorroMeta $ahorro, AhorroProgramado $programado)
    {
        $fecha = \Carbon\Carbon::parse($programado->fecha_inicio);
        $numCuotas = $programado->num_cuotas ?? 1;

        for ($i = 0; $i < $numCuotas; $i++) {
            AporteAhorro::create([
                'ahorro_meta_id' => $ahorro->ahorro_meta_id,
                'monto' => $programado->monto_programado,
                'fecha_registro' => $fecha->copy(),
            ]);

            // Sumar la frecuencia
            switch ($programado->frecuencia) {
                case 'semanal':
                    $fecha->addWeek();
                    break;
                case 'quincenal':
                    $fecha->addWeeks(2);
                    break;
                case 'mensual':
                    $fecha->addMonth();
                    break;
                case 'anual':
                    $fecha->addYear();
                    break;
                default:
                    $fecha->addMonth();
            }
        }
    }

    // Mostrar detalle de un ahorro
    public function show($id)
    {
        $ahorro = AhorroMeta::with('ahorroProgramados', 'aporteAhorros')->findOrFail($id);

        $porcentaje = $ahorro->monto_meta
            ? round(($ahorro->total_acumulado / $ahorro->monto_meta) * 100, 2)
            : 0;

        return view('ahorros.show', compact('ahorro', 'porcentaje'));
    }

    // Editar, actualizar y eliminar igual que antes
    public function edit($id)
    {
        $ahorro = AhorroMeta::with('ahorroProgramados')->findOrFail($id);
        return view('ahorros.edit', compact('ahorro'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'concepto' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'monto_meta' => 'nullable|numeric|min:0',
            'fecha_meta' => 'nullable|date',
            'monto_programado' => 'required|numeric|min:1',
            'frecuencia' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date',
            'num_cuotas' => 'nullable|integer|min:1',
        ]);

        $ahorro = AhorroMeta::findOrFail($id);
        $ahorro->update([
            'concepto' => $validated['concepto'],
            'descripcion' => $validated['descripcion'] ?? null,
            'monto_meta' => $validated['monto_meta'] ?? null,
            'fecha_meta' => $validated['fecha_meta'] ?? null,
        ]);

        $programado = $ahorro->ahorroProgramados->first();
        if ($programado) {
            $programado->update([
                'monto_programado' => $validated['monto_programado'],
                'frecuencia' => $validated['frecuencia'],
                'fecha_inicio' => $validated['fecha_inicio'],
                'fecha_fin' => $validated['fecha_fin'] ?? null,
                'num_cuotas' => $validated['num_cuotas'] ?? null,
            ]);
        }

        return redirect()->route('ahorros.index')->with('success', 'Ahorro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $ahorro = AhorroMeta::findOrFail($id);
        $ahorro->delete();

        return redirect()->route('ahorros.index')->with('success', 'Ahorro eliminado correctamente.');
    }
}
