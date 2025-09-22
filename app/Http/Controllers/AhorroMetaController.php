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
        $userId = Auth::id();
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
            'monto_meta' => 'required|numeric|min:1',
            'frecuencia' => 'required|string|max:30',
            'fecha_meta' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $meta = AhorroMeta::create([
            'usuario_id' => Auth::id(),
            'concepto' => $request->concepto,
            'descripcion' => $request->descripcion,
            'monto_meta' => $request->monto_meta,
            'frecuencia' => $request->frecuencia,
            'fecha_meta' => $request->fecha_meta,
            'estado' => 'Activo',
            'total_acumulado' => 0,
        ]);

        // Generar automáticamente los aportes
        $this->generarAportes($meta);

        return redirect()->route('ahorros.index')->with('success', 'Ahorro creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $ahorro = AhorroMeta::where('usuario_id', $userId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'concepto' => 'required|string|max:60',
            'descripcion' => 'nullable|string|max:100',
            'monto_meta' => 'required|numeric|min:1',
            'frecuencia' => 'required|string|max:30',
            'fecha_meta' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ahorro->update($request->only(['concepto','descripcion','monto_meta','frecuencia','fecha_meta']));

        // Recalcular aportes pendientes (no tocar los ya pagados)
        $this->recalcularAportes($ahorro);

        return redirect()->route('ahorros.index')->with('success', 'Ahorro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $userId = Auth::id();
        $ahorro = AhorroMeta::where('usuario_id', $userId)->findOrFail($id);

        $ahorro->delete();

        return redirect()->route('ahorros.index')->with('success', 'Ahorro eliminado correctamente.');
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
            case 'Mensual':
                $cantidadCuotas = $fechaInicio->diffInMonths($fechaFin) + 1;
                $periodo = 'month';
                break;
        }

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

            $fecha->add(1, $periodo);
        }
    }

    private function recalcularAportes(AhorroMeta $meta)
    {
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
    }
}
