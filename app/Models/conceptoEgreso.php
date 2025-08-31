<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class conceptoEgreso extends Model
{
    protected $table = 'concepto_egreso';
    protected $primaryKey = 'concepto_egreso_id';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'usuario_id',
    ];
    public function gastos()
    {
        return $this->hasMany(Gastos::class, 'concepto_egreso_id');
    }
}
