<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoIngreso extends Model
{
    use HasFactory;
    protected $table = 'concepto_ingreso';
    protected $primaryKey = 'concepto_ingreso_id';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'usuario_id'
    ];

    public function proyeccion()
    {
        return $this->belongsTo(ProyeccionIngreso::class, 'concepto_ingreso_id', 'concepto_ingreso_id');
    }

    public function conceptoIngreso() {
        return $this->belongsTo(\App\Models\ConceptoIngreso::class, 'concepto_ingreso_id');
    }
}


