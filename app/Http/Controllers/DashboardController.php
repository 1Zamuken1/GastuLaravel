<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingreso;
use App\Models\Egreso;
use App\Models\ProyeccionIngreso;
use App\Models\AhorroMeta;
use App\Models\ConceptoEgreso;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $mesActual = now()->month;
        $anioActual = now()->year;

        // Ingresos reales del mes actual
        $ingresosMesActual = Ingreso::where('usuario_id', $userId)
            ->whereMonth('fecha_registro', $mesActual)
            ->whereYear('fecha_registro', $anioActual)
            ->sum('monto');

        // Proyecciones de ingreso del mes actual
        $proyeccionesMesActual = ProyeccionIngreso::where('usuario_id', $userId)
            ->whereMonth('fecha_fin', $mesActual)
            ->whereYear('fecha_fin', $anioActual)
            ->sum('monto_programado');

        // Total ingresos (reales + proyecciones) del mes actual
        $totalIngresos = $ingresosMesActual + $proyeccionesMesActual;

        // Egresos del mes actual
        $totalEgresos = Egreso::where('usuario_id', $userId)
            ->whereMonth('fecha_registro', $mesActual)
            ->whereYear('fecha_registro', $anioActual)
            ->sum('monto');

        // Total ahorros (todas las metas del usuario)
        $totalAhorros = AhorroMeta::where('usuario_id', $userId)
            ->sum('total_acumulado');

        // Distribución de ahorros (todas las metas del usuario)
        $ahorrosUsuario = AhorroMeta::where('usuario_id', $userId)->get();

        $ahorroLabels = [];
        $ahorroData = [];
        foreach ($ahorrosUsuario as $meta) {
            $ahorroLabels[] = $meta->concepto;
            $ahorroData[] = (float) $meta->total_acumulado;
        }

        // Saldo neto del mes actual
        $saldoNeto = $totalIngresos - $totalEgresos;

        // Ingresos/Egresos mensuales (para gráfico anual)
        $ingresosPorMes = Ingreso::where('usuario_id', $userId)
            ->selectRaw('MONTH(fecha_registro) as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        $egresosPorMes = Egreso::where('usuario_id', $userId)
            ->selectRaw('MONTH(fecha_registro) as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        // Asegura que todos los meses estén presentes (1-12)
        $ingresosMes = [];
        $egresosMes = [];
        for ($i = 1; $i <= 12; $i++) {
            $ingresosMes[] = isset($ingresosPorMes[$i]) ? (float)$ingresosPorMes[$i] : 0;
            $egresosMes[] = isset($egresosPorMes[$i]) ? (float)$egresosPorMes[$i] : 0;
        }

        // Gastos por categoría (solo del mes actual)
        $gastosPorCategoria = Egreso::where('usuario_id', $userId)
            ->whereMonth('fecha_registro', $mesActual)
            ->whereYear('fecha_registro', $anioActual)
            ->selectRaw('concepto_egreso_id, SUM(monto) as total')
            ->groupBy('concepto_egreso_id')
            ->get();

        // Prepara datos para el treemap
        $treemapData = [];
        foreach ($gastosPorCategoria as $gasto) {
            $concepto = ConceptoEgreso::find($gasto->concepto_egreso_id);
            $nombre = $concepto ? $concepto->nombre : 'Sin categoría';
            $treemapData[] = [
                'x' => $nombre,
                'y' => (float) $gasto->total,
            ];
        }

        return view('Dashboard.dashboard', [
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'totalAhorros' => $totalAhorros,
            'saldoNeto' => $saldoNeto,
            'ingresosMes' => $ingresosMes,
            'egresosMes' => $egresosMes,
            'ingresosMesActual' => $ingresosMesActual,
            'proyeccionesMesActual' => $proyeccionesMesActual,
            'ahorroLabels' => $ahorroLabels,
            'ahorroData' => $ahorroData,
            'treemapData' => $treemapData,
        ]);
    }
}