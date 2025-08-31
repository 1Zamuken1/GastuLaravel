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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `pa_verificar_limite_egreso_mensual`(
    IN p_usuario_id BIGINT,
    IN p_concepto_id BIGINT,
    IN p_limite DECIMAL(12,2),
    IN p_anio INT,
    IN p_mes INT
)
BEGIN
    DECLARE total_gastado DECIMAL(12,2) DEFAULT 0;

    SELECT SUM(e.monto) INTO total_gastado
    FROM egreso e
    INNER JOIN concepto_egreso ce ON e.concepto_egreso_id = ce.concepto_egreso_id
    WHERE ce.usuario_id = p_usuario_id
      AND e.concepto_egreso_id = p_concepto_id
      AND YEAR(e.fecha_registro) = p_anio
      AND MONTH(e.fecha_registro) = p_mes;

    IF total_gastado IS NULL THEN
        SET total_gastado = 0;
    END IF;

    IF total_gastado > p_limite THEN
        SELECT CONCAT('Límite superado. Gastado: $', total_gastado) AS resultado;
    ELSE
        SELECT CONCAT('Dentro del límite. Gastado: $', total_gastado) AS resultado;
    END IF;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS pa_verificar_limite_egreso_mensual");
    }
};
