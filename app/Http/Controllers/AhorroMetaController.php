<?php

namespace App\Http\Controllers;

use App\Models\AhorroMeta;
use App\Models\AporteAhorro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AhorroMetaController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // solo modificar lo de la autenticacion, logica bien hecha
        $ahorros = AhorroMeta::where('usuario_id', $userId)->get();

        foreach ($ahorros as $ahorro) {
            $ahorro->porcentaje_avance = $this->calcularPorcentaje($ahorro);
        }

        return view('ahorros.ahorros', compact('ahorros'));
    }

    public function show($id)
    {
        $userId = Auth::id();
        $ahorro = AhorroMeta::where('usuario_id', $userId)->findOrFail($id);
        $ahorro->porcentaje_avance = $this->calcularPorcentaje($ahorro);

        return view('ahorros.partials.showModal', compact('ahorro'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'concepto' => 'required|string|max:60',
            'descripcion' => 'nullable|string|max:100',
            'monto_meta' => 'required|numeric|min:0.01',
            'frecuencia' => 'required|string|in:Diario,Semanal,Quincenal,Mensual,Trimestral,Semestral,Anual',
            'fecha_meta' => 'required|date|after:today'
        ], [
            'concepto.required' => 'El concepto es obligatorio',
            'monto_meta.required' => 'El monto meta es obligatorio',
            'monto_meta.min' => 'El monto meta debe ser mayor a 0',
            'frecuencia.required' => 'La frecuencia es obligatoria',
            'fecha_meta.required' => 'La fecha meta es obligatoria',
            'fecha_meta.after' => 'La fecha meta debe ser posterior a hoy'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Por favor corrige los errores del formulario');
        }

        try {
            $meta = AhorroMeta::create([
                'usuario_id' => Auth::id(),
                'concepto' => trim($request->concepto),
                'descripcion' => $request->descripcion ? trim($request->descripcion) : null,
                'monto_meta' => (float) $request->monto_meta,
                'frecuencia' => $request->frecuencia,
                'fecha_creacion' => Carbon::now(),
                'fecha_meta' => Carbon::parse($request->fecha_meta),
                'estado' => 'Activo', // Estado por defecto
                'total_acumulado' => 0,
                'cantidad_cuotas' => 0
            ]);

            if ($meta && $meta->ahorro_meta_id) {
                $this->generarAportes($meta);
                return redirect()->route('ahorros.index')
                    ->with('success', 'Ahorro creado correctamente');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No se pudo crear el ahorro. Intenta nuevamente');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el ahorro: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $ahorro = AhorroMeta::where('usuario_id', $userId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'concepto' => 'required|string|max:60',
            'descripcion' => 'nullable|string|max:100',
            'monto_meta' => 'required|numeric|min:0.01',
            'frecuencia' => 'required|string|in:Diario,Semanal,Quincenal,Mensual,Trimestral,Semestral,Anual',
            'fecha_meta' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $ahorro->update([
                'concepto' => trim($request->concepto),
                'descripcion' => $request->descripcion ? trim($request->descripcion) : null,
                'monto_meta' => (float) $request->monto_meta,
                'frecuencia' => $request->frecuencia,
                'fecha_meta' => Carbon::parse($request->fecha_meta)
            ]);

            // Recalcular aportes pendientes (no tocar los ya pagados)
            $this->recalcularAportes($ahorro);

            return redirect()->route('ahorros.index')
                ->with('success', 'Ahorro actualizado correctamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el ahorro: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $userId = Auth::id();
            $ahorro = AhorroMeta::where('usuario_id', $userId)->findOrFail($id);

            // Eliminar los aportes relacionados primero
            AporteAhorro::where('ahorro_meta_id', $ahorro->ahorro_meta_id)->delete();
            
            // Eliminar el ahorro
            $ahorro->delete();

            return redirect()->route('ahorros.index')
                ->with('success', 'Ahorro eliminado correctamente');

        } catch (\Exception $e) {
            return redirect()->route('ahorros.index')
                ->with('error', 'Error al eliminar el ahorro: ' . $e->getMessage());
        }
    }

    private function calcularPorcentaje($ahorro)
    {
        if ($ahorro->monto_meta > 0) {
            $avance = ($ahorro->total_acumulado / $ahorro->monto_meta) * 100;
            return number_format(min($avance, 100), 0) . '%';
        }
        return "0%";
    }

    private function generarAportes(AhorroMeta $meta)
    {
        try {
            $fechaInicio = Carbon::now();
            $fechaFin = Carbon::parse($meta->fecha_meta);

            $cantidadCuotas = 0;
            $periodo = null;
            
            switch ($meta->frecuencia) {
                case 'Diario':
                    $cantidadCuotas = $fechaInicio->diffInDays($fechaFin) + 1;
                    $periodo = 'day';
                    break;
                case 'Semanal':
                    $cantidadCuotas = $fechaInicio->diffInWeeks($fechaFin) + 1;
                    $periodo = 'week';
                    break;
                case 'Quincenal':
                    $cantidadCuotas = ceil($fechaInicio->diffInDays($fechaFin) / 15) + 1;
                    $periodo = 'days';
                    $periodoDias = 15;
                    break;
                case 'Mensual':
                   $cantidadCuotas = $fechaInicio->diffInMonths($fechaFin) + 1;
                   $periodo = 'month';
                   break;
                case 'Trimestral':
                   $cantidadCuotas = ceil($fechaInicio->diffInMonths($fechaFin) / 3) + 1;
                   $periodo = 'months';
                   $periodoMeses = 3;
                   break;
                case 'Semestral':
                   $cantidadCuotas = ceil($fechaInicio->diffInMonths($fechaFin) / 6) + 1;
                   $periodo = 'months';
                   $periodoMeses = 6;
                   break;
                case 'Anual':
                  $cantidadCuotas = ceil($fechaInicio->diffInYears($fechaFin)) + 1;
                  $periodo = 'year';
                  break;
}
            // Actualizar cantidad de cuotas
            $meta->cantidad_cuotas = $cantidadCuotas;
            $meta->save();

            $aporteAsignado = $meta->monto_meta / $cantidadCuotas;
            $fecha = $fechaInicio->copy();

            for ($i = 0; $i < $cantidadCuotas; $i++) {
                $fechaLimite = $fecha->copy()->endOfDay();

                AporteAhorro::create([
                    'ahorro_meta_id' => $meta->ahorro_meta_id,
                    'aporte_asignado' => $aporteAsignado,
                    'fecha_limite' => $fechaLimite,
                    'estado' => 'Pendiente',
                ]);

                // Avanzar la fecha según la frecuencia
                if ($meta->frecuencia == 'Quincenal') {
                    $fecha->addDays($periodoDias);
                } else {
                    $fecha->add(1, $periodo);
                }
            }

        } catch (\Exception $e) {
            // En caso de error, no generar aportes pero mantener el ahorro
            // Se puede manejar manualmente después
        }
    }

    private function recalcularAportes(AhorroMeta $meta)
    {
        try {
            $restante = $meta->monto_meta - $meta->total_acumulado;

            $pendientes = AporteAhorro::where('ahorro_meta_id', $meta->ahorro_meta_id)
                ->where('estado', 'Pendiente')
                ->count();

            if ($pendientes > 0 && $restante > 0) {
                $nuevoMonto = $restante / $pendientes;

                AporteAhorro::where('ahorro_meta_id', $meta->ahorro_meta_id)
                    ->where('estado', 'Pendiente')
                    ->update(['aporte_asignado' => $nuevoMonto]);
            }
        } catch (\Exception $e) {
            // Manejar error silenciosamente
        }
    }
}