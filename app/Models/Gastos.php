<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gastos extends Model
{
    use HasFactory;
    protected $table = 'egreso';
    protected $primaryKey = 'egreso_id';
    public $timestamps = false;
    protected $fillable = [
        'tipo',
        'monto',
        'descripcion',
        'fecha_registro',
        'concepto_egreso_id',
    ];
    // Relación:muchos gastos pertenecen a un concepto de egreso
    public function conceptoEgreso()
    {
        return $this->belongsTo(conceptoEgreso::class, 'concepto_egreso_id');
    }
    
}
