<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AhorroMeta extends Model
{
    protected $table = 'ahorro_meta';
    protected $primaryKey = 'ahorro_meta_id';
    public $timestamps = false;

    protected $casts = [
        'usuario_id' => 'int',
        'monto_meta' => 'float',
        'total_acumulado' => 'float',
        'fecha_creacion' => 'datetime',
        'fecha_meta' => 'date',
        'activa' => 'bool'
    ];

    protected $fillable = [
        'usuario_id',
        'concepto',
        'descripcion',
        'monto_meta',
        'total_acumulado',
        'fecha_creacion',
        'fecha_meta',
        'activa'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function ahorroProgramados(): HasMany
    {
        return $this->hasMany(AhorroProgramado::class, 'ahorro_meta_id');
    }

    public function aporteAhorros(): HasMany
    {
        return $this->hasMany(AporteAhorro::class, 'ahorro_meta_id');
    }
}
