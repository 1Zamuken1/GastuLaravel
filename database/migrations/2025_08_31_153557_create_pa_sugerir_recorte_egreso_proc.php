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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_sugerir_recorte_egreso`(
    IN p_usuario_id BIGINT,
    IN p_concepto_egreso_id BIGINT,
    IN p_anio INT,
    IN p_mes INT
)
BEGIN
    DECLARE total_mes DECIMAL(12,2) DEFAULT 0;
    DECLARE total_concepto DECIMAL(12,2) DEFAULT 0;
    DECLARE porcentaje DECIMAL(5,2);
    DECLARE nombre_concepto VARCHAR(50);

    SELECT SUM(e.monto) INTO total_mes
    FROM egreso e
    INNER JOIN concepto_egreso ce ON e.concepto_egreso_id = ce.concepto_egreso_id
    WHERE ce.usuario_id = p_usuario_id
      AND YEAR(e.fecha_registro) = p_anio
      AND MONTH(e.fecha_registro) = p_mes;

    SELECT SUM(e.monto) INTO total_concepto
    FROM egreso e
    INNER JOIN concepto_egreso ce ON e.concepto_egreso_id = ce.concepto_egreso_id
    WHERE ce.usuario_id = p_usuario_id
      AND e.concepto_egreso_id = p_concepto_egreso_id
      AND YEAR(e.fecha_registro) = p_anio
      AND MONTH(e.fecha_registro) = p_mes;

    SELECT nombre INTO nombre_concepto
    FROM concepto_egreso
    WHERE concepto_egreso_id = p_concepto_egreso_id;

    IF total_mes IS NULL THEN SET total_mes = 0; END IF;
    IF total_concepto IS NULL THEN SET total_concepto = 0; END IF;

    IF total_mes > 0 THEN
        SET porcentaje = (total_concepto / total_mes) * 100;

        IF porcentaje > 50 THEN
            SELECT CONCAT('Gastos altos en \"', nombre_concepto, '\" (', ROUND(porcentaje, 2), '%). Considera reducir.') AS sugerencia;
        ELSE
            SELECT CONCAT('Gastos razonables en \"', nombre_concepto, '\" (', ROUND(porcentaje, 2), '%).') AS sugerencia;
        END IF;
    ELSE
        SELECT 'No hay egresos registrados este mes.' AS sugerencia;
    END IF;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS pa_sugerir_recorte_egreso");
    }
};
