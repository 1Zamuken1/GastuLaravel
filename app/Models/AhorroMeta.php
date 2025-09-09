<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//tabla padre-
//  ahorro programado y aporte ahorro->tablas hijas
class AhorroMeta extends Model
{
    use HasFactory;
//nombre del modelo realcionado con la tabla, variable que define el nombe de la tabla
    protected $table = 'ahorro_meta';
     //indica el id y como va a cambiar por la api pero no por el usuario
    protected $primaryKey = 'ahorro_meta_id';
    public $timestamps = false;

     //que campos son alterados, son iguales a los de ba bd
    protected $fillable = [
        'concepto',
        'descripcion',
        'monto_meta',
        'total_acumulado',
        'fecha_creacion',
        'fecha_meta',
        'activa',
        'usuario_id'
    ];
     //funcion eloquent para relacionar con ahorro meta la llave foranea
//blongsTo me guarda la clave foranea
    public function ahorroprogramado()
    {
        return $this->hasMany(AhorroProgramado::class, 'ahorro_meta_id');
    }

    public function aporteahorro()
    {
        return $this->hasMany(AporteAhorro::class, 'ahorro_meta_id');
    }
}
