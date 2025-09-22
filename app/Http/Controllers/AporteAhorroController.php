<?php

namespace App\Http\Controllers;

use App\Models\AporteAhorro;
use App\Models\AhorroMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AporteAhorroController extends Controller
{
    public function index($ahorro_meta_id)
    {
        $aportes = AporteAhorro::where('ahorro_meta_id', $ahorro_meta_id)->get();

        if ($aportes->isEmpty()) {
            return response()->json([
                'message' => 'No hay aportes registrados para este ahorro',
                'status' => 200,
            ], 200);
        }

        return response()->json([
            'aportes' => $aportes,
            'status' => 200,
        ], 200);
    }

    // Registrar aporte real (cuando el usuario llena manualmente el campo "aporte")
    public function update(Request $request, $id)
    {
        $aporte = AporteAhorro::find($id);

        if (! $aporte) {
            return response()->json([
                'message' => 'Aporte no encontrado',
                'status' => 404,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'aporte' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $aporte->aporte = $request->aporte;

        $this->evaluarEstadoYAjustar($aporte);

        return response()->json([
            'message' => 'Aporte actualizado correctamente',
            'aporte' => $aporte,
            'status' => 200,
        ], 200);
    }

    // Registrar el aporte asignado directo (cuando el usuario da clic al botón "aporte asignado")
    public function aportarAsignado($id)
    {
        $aporte = AporteAhorro::find($id);

        if (! $aporte) {
            return response()->json([
                'message' => 'Aporte no encontrado',
                'status' => 404,
            ], 404);
        }

        $aporte->aporte = $aporte->aporte_asignado;

        $this->evaluarEstadoYAjustar($aporte);

        return response()->json([
            'message' => 'Aporte asignado registrado correctamente',
            'aporte' => $aporte,
            'status' => 200,
        ], 200);
    }

    private function evaluarEstadoYAjustar(AporteAhorro $aporte)
    {
        $hoy = Carbon::now();

        if ($hoy->lte($aporte->fecha_limite)) {
            $aporte->estado = 'Completada';
        } else {
            $aporte->estado = 'Perdida';
            $this->recalcularProximasCuotas($aporte->ahorro_meta_id);
        }

        $aporte->save();

        $meta = $aporte->ahorro_meta;
        $meta->total_acumulado += $aporte->aporte;
        $meta->save();
    }

    private function recalcularProximasCuotas($ahorro_meta_id)
    {
        $meta = AhorroMeta::find($ahorro_meta_id);

        if (! $meta) return;

        $perdidas = AporteAhorro::where('ahorro_meta_id', $ahorro_meta_id)
            ->where('estado', 'Perdida')
            ->count();

        if ($perdidas >= 5) {
            $meta->estado = 'Inactivo';
            $meta->save();
            return;
        }

        $restante = $meta->monto_meta - $meta->total_acumulado;

        $pendientes = AporteAhorro::where('ahorro_meta_id', $ahorro_meta_id)
            ->where('estado', 'Pendiente')
            ->count();

        if ($pendientes > 0 && $restante > 0) {
            $nuevoMonto = $restante / $pendientes;

            AporteAhorro::where('ahorro_meta_id', $ahorro_meta_id)
                ->where('estado', 'Pendiente')
                ->update(['aporte_asignado' => $nuevoMonto]);
        }
    }
}
