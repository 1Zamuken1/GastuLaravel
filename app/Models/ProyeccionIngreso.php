<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyeccionIngreso extends Model
{
    use HasFactory;

    protected $table = 'proyeccion_ingreso';

    protected $primaryKey = 'proyeccion_ingreso_id';

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
        'ultima_generacion',
        'concepto_ingreso_id',
    ];

    public function proyeccion()
    {
        return $this->belongsTo(ProyeccionIngreso::class, 'concepto_ingreso_id', 'concepto_ingreso_id');
    }

    public function conceptoIngreso() {
        return $this->belongsTo(\App\Models\ConceptoIngreso::class, 'concepto_ingreso_id');
    }
}
