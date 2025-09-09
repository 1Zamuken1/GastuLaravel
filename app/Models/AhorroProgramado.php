<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AhorroProgramado extends Model
{
    use HasFactory;

    protected $table = 'ahorro_programado';
    protected $primaryKey = 'ahorro_programado_id';
    public $timestamps = false;

    protected $fillable = [
        'monto_programado',
        'frecuencia',
        'fecha_inicio',
        'fecha_fin',
        'num_cuotas',
        'ultimo_aporte_generado',
        'ahorro_meta_id'
    ];

    public function ahorrometa()
    {
        return $this->belongsTo(AhorroMeta::class, 'ahorro_meta_id'); //esta relaciona el ahorro programado con el ahorro meta
    }

}
