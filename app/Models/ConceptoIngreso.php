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
}
