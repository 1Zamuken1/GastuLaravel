<?php

namespace App\Http\Controllers;

use App\Models\AporteAhorro;
use App\Models\AhorroMeta;
use Illuminate\Http\Request;

class AporteAhorroController extends Controller
{
    // Mostrar todos los aportes de un ahorro
    public function index($ahorroMetaId)
    {
        $meta = AhorroMeta::with('aporteAhorros')->findOrFail($ahorroMetaId);
        $aportes = $meta->aporteAhorros;

        return view('ahorros.aportes.indexAporte', compact('meta', 'aportes'));
    }

    // Mostrar detalle de un aporte
    public function show($id)
    {
        $aporte = AporteAhorro::with('ahorro_meta')->findOrFail($id);
        return view('ahorros.aportes.showAporte', compact('aporte'));
    }

    // Mostrar formulario de edición de un aporte
    public function edit($id)
    {
        $aporte = AporteAhorro::with('ahorro_meta')->findOrFail($id);
        return view('ahorros.aportes.editAporte', compact('aporte'));
    }

    // Actualizar un aporte
    public function update(Request $request, $id)
    {
        $aporte = AporteAhorro::findOrFail($id);

        $validated = $request->validate([
            'monto' => 'required|numeric|min:1',
            'fecha_registro' => 'required|date'
        ]);

        $aporte->update($validated);

        // Recalcular total acumulado de la meta
        $meta = $aporte->ahorro_meta;
        $meta->update([
            'total_acumulado' => $meta->aporteAhorros()->sum('monto')
        ]);

        return redirect()->route('aportes.index', $aporte->ahorro_meta_id)
                         ->with('success', 'Aporte actualizado correctamente.');
    }

    // Eliminar un aporte
    public function destroy($id)
    {
        $aporte = AporteAhorro::findOrFail($id);
        $ahorroMetaId = $aporte->ahorro_meta_id;
        $aporte->delete();

        // Recalcular total acumulado de la meta
        $meta = AhorroMeta::find($ahorroMetaId);
        $meta->update([
            'total_acumulado' => $meta->aporteAhorros()->sum('monto')
        ]);

        return redirect()->route('aportes.index', $ahorroMetaId)
                         ->with('success', 'Aporte eliminado correctamente.');
    }

    // Función para aportar cuota desde el botón
    public function pagarCuota($id)
    {
        $aporte = AporteAhorro::findOrFail($id);

        // Aquí se podría agregar lógica extra si fuera necesario
        // Por ahora simplemente recalculamos el total acumulado
        $meta = $aporte->ahorro_meta;
        $meta->update([
            'total_acumulado' => $meta->aporteAhorros()->sum('monto')
        ]);

        return redirect()->route('aportes.index', $meta->ahorro_meta_id)
                         ->with('success', 'Se ha aportado la cuota correctamente.');
    }
}
