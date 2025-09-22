<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseQueryService
{
    protected $usuarioId;

    /**
     * Constructor: recibe el ID del usuario autenticado
     */
    public function __construct($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Retorna un contexto general del usuario:
     * - Información básica
     * - Resumen de ingresos
     * - Resumen de egresos
     * - Resumen de metas de ahorro
     */
    public function obtenerContextoUsuario()
    {
        $contexto = [];

        // Información básica del usuario
        $usuario = DB::table('usuario')->where('usuario_id', $this->usuarioId)->first();
        $contexto['usuario'] = [
            'nombre' => $usuario->nombre ?? null,
            'correo' => $usuario->correo ?? null,
            'fecha_registro' => $usuario->fecha_registro ?? null
        ];

        // Resúmenes
        $contexto['ingresos'] = $this->obtenerResumenIngresos();
        $contexto['egresos'] = $this->obtenerResumenEgresos();
        $contexto['ahorros'] = $this->obtenerResumenAhorros();

        return $contexto;
    }

    /**
     * Método genérico para consultar cualquier tabla filtrada por usuario_id
     */
    public function consultarTabla($tabla, $columnaUsuario = 'usuario_id', $filtros = [])
    {
        try {
            $query = DB::table($tabla)->where($columnaUsuario, $this->usuarioId);

            foreach ($filtros as $campo => $valor) {
                $query->where($campo, $valor);
            }

            return $query->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Método genérico para consultar una tabla con join
     * Ejemplo: egreso con concepto_egreso
     */
    public function consultarTablaConJoin($tablaPrincipal, $tablaJoin, $campoRelacionPrincipal, $campoRelacionJoin, $columnas = ['*'], $filtros = [])
    {
        try {
            $query = DB::table($tablaPrincipal)
                ->join($tablaJoin, $campoRelacionPrincipal, '=', $campoRelacionJoin)
                ->where($tablaPrincipal . '.usuario_id', $this->usuarioId);

            foreach ($filtros as $campo => $valor) {
                $query->where($campo, $valor);
            }

            return $query->select($columnas)->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Devuelve las columnas de una tabla
     */
    public function obtenerColumnasTabla($tabla)
    {
        try {
            return Schema::getColumnListing($tabla);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Ejecuta una consulta SQL personalizada y asegura que filtre por usuario_id
     */
    public function ejecutarConsultaPersonalizada($sql, $parametros = [])
    {
        try {
            if (!str_contains(strtolower($sql), 'where')) {
                $sql .= " WHERE usuario_id = ?";
                $parametros[] = $this->usuarioId;
            } else {
                $sql .= " AND usuario_id = ?";
                $parametros[] = $this->usuarioId;
            }

            return DB::select($sql, $parametros);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Resumen de ingresos del usuario
     */
    private function obtenerResumenIngresos()
    {
        $ingresos = DB::table('ingreso as i')
            ->leftJoin('concepto_ingreso as c', 'i.concepto_ingreso_id', '=', 'c.concepto_ingreso_id')
            ->where('i.usuario_id', $this->usuarioId)
            ->select('i.monto', 'i.descripcion', 'i.fecha_registro', 'c.nombre as concepto')
            ->orderBy('i.fecha_registro', 'desc')
            ->limit(5)
            ->get();

        $totalIngresos = DB::table('ingreso')
            ->where('usuario_id', $this->usuarioId)
            ->sum('monto');

        return [
            'total' => $totalIngresos,
            'recientes' => $ingresos,
            'count' => $ingresos->count()
        ];
    }

    /**
     * Resumen de egresos del usuario
     */
    private function obtenerResumenEgresos()
    {
        $egresos = DB::table('egreso as e')
            ->leftJoin('concepto_egreso as c', 'e.concepto_egreso_id', '=', 'c.concepto_egreso_id')
            ->where('e.usuario_id', $this->usuarioId)
            ->select('e.monto', 'e.descripcion', 'e.fecha_registro', 'c.nombre as concepto')
            ->orderBy('e.fecha_registro', 'desc')
            ->limit(5)
            ->get();

        $totalEgresos = DB::table('egreso')
            ->where('usuario_id', $this->usuarioId)
            ->sum('monto');

        return [
            'total' => $totalEgresos,
            'recientes' => $egresos,
            'count' => $egresos->count()
        ];
    }

    /**
     * Resumen de metas de ahorro del usuario
     */
    private function obtenerResumenAhorros()
    {
        $ahorros = DB::table('ahorro_meta')
            ->where('usuario_id', $this->usuarioId)
            ->select('concepto', 'monto_meta', 'total_acumulado', 'estado', 'fecha_meta')
            ->orderBy('fecha_creacion', 'desc')
            ->limit(5)
            ->get();

        return [
            'metas' => $ahorros,
            'count' => $ahorros->count()
        ];
    }
}
