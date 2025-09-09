<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AporteAhorro extends Model
{
    use HasFactory;
    protected $table = 'aporte_ahorro';
    protected $primaryKey = 'aporte_ahorro_id';
    public $timestamps = false;

    protected $fillable = [
        'monto',
        'fecha_registro',
        'ahorro_meta_id'
    ];
    
    public function ameta()
    {
        return $this->belongsTo(AhorroMeta::class, 'ahorro_meta_id');
    }

}
