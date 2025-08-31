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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_porcentaje_avance_ahorro`(
    IN p_usuario_id BIGINT
)
BEGIN
    SELECT 
        ahorro_meta_id,
        concepto,
        monto_meta,
        total_acumulado,
        LEAST(100, GREATEST(0, ROUND((total_acumulado / monto_meta) * 20, 0) * 5)) AS porcentaje_avance,
        CASE
            WHEN monto_meta <= 0 THEN 'Meta inválida'
            WHEN total_acumulado >= monto_meta THEN 'Completada'
            ELSE 'En progreso'
        END AS estado_meta
    FROM ahorro_meta
    WHERE usuario_id = p_usuario_id
      AND monto_meta > 0;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS pa_porcentaje_avance_ahorro");
    }
};
