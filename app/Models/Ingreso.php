<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;

    protected $table = 'ingreso';

    protected $primaryKey = 'ingreso_id';

    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'monto',
        'descripcion',
        'fecha_registro',
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
