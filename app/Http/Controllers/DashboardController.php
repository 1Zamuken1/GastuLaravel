<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingreso;
use App\Models\Egreso;
use App\Models\ProyeccionIngreso;
use App\Models\AhorroMeta;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Totales
        $totalIngresos = Ingreso::where('usuario_id', $userId)->sum('monto');
        //$totalEgresos = Egreso::where('usuario_id', $userId)->sum('monto');
        //$totalAhorros = AhorroMeta::where('usuario_id', $userId)->sum('monto');

        // Saldo neto
        //$saldoNeto = $totalIngresos - $totalEgresos;

        // Ingresos/Egresos mensuales (para gráfico)
        $ingresosPorMes = Ingreso::where('usuario_id', $userId)
            ->selectRaw('MONTH(fecha_registro) as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $egresosPorMes = Egreso::where('usuario_id', $userId)
            ->selectRaw('MONTH(fecha_registro) as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        // Ahorros por categoría (para gráfico)
        // $ahorrosPorCategoria = AhorroMeta::where('usuario_id', $userId)
        //     ->selectRaw('categoria, SUM(monto) as total')
        //     ->groupBy('categoria')
        //     ->pluck('total', 'categoria');

        // Gastos por categoría (para gráfico)
        // $gastosPorCategoria = Egreso::where('usuario_id', $userId)
        //     ->selectRaw('categoria, SUM(monto) as total')
        //     ->groupBy('categoria')
        //     ->pluck('total', 'categoria');

        return view('Dashboard.dashboard', [
            'totalIngresos' => $totalIngresos,
            //'totalEgresos' => $totalEgresos,
            //'totalAhorros' => $totalAhorros,
            //'saldoNeto' => $saldoNeto,
            'ingresosPorMes' => $ingresosPorMes,
            //'egresosPorMes' => $egresosPorMes,
            //'ahorrosPorCategoria' => $ahorrosPorCategoria,
            //'gastosPorCategoria' => $gastosPorCategoria,
        ]);
    }
}