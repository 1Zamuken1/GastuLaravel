<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_total_ingresos_egresos_mensual`(
    IN p_usuario_id BIGINT,
    IN p_anio INT,
    IN p_mes INT
)
BEGIN
    SELECT 
        'Ingreso' AS tipo,
        ci.nombre AS concepto,
        SUM(i.monto) AS total
    FROM ingreso i
    LEFT JOIN concepto_ingreso ci ON i.concepto_ingreso_id = ci.concepto_ingreso_id
    WHERE 
        ci.usuario_id = p_usuario_id
        AND YEAR(i.fecha_registro) = p_anio
        AND MONTH(i.fecha_registro) = p_mes
    GROUP BY ci.nombre

    UNION ALL

    SELECT 
        'Egreso' AS tipo,
        ce.nombre AS concepto,
        SUM(e.monto) AS total
    FROM egreso e
    LEFT JOIN concepto_egreso ce ON e.concepto_egreso_id = ce.concepto_egreso_id
    WHERE 
        ce.usuario_id = p_usuario_id
        AND YEAR(e.fecha_registro) = p_anio
        AND MONTH(e.fecha_registro) = p_mes
    GROUP BY ce.nombre;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS pa_total_ingresos_egresos_mensual");
    }
};
