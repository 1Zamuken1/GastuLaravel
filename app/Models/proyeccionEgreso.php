<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class proyeccionEgreso extends Model
{
    protected $table = 'proyeccion_egreso';
    protected $primaryKey = 'proyeccion_egreso_id';
    public $timestamps = false;
    protected $fillable = [
    'monto_programado',
    'descripcion',
    'frecuencia',
    'dia_recurrencia',
    'fecha_inicio',
    'fecha_fin',
    'activo',
    'fecha_creacion', 
    'ultima_generacion_exitosa',
    'concepto_egreso_id',
];
    public function conceptoEgreso()
    {
        return $this->belongsTo(conceptoEgreso::class, 'concepto_egreso_id');
    }
}
