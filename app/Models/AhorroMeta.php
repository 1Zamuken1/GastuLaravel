<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AhorroMetum
 * 
 * @property int $ahorro_meta_id
 * @property int $usuario_id
 * @property string $concepto
 * @property string|null $descripcion
 * @property float $monto_meta
 * @property float|null $total_acumulado
 * @property string $frecuencia
 * @property Carbon $fecha_creacion
 * @property Carbon $fecha_meta
 * @property string|null $estado
 * @property int|null $cantidad_cuotas
 * 
 * @property Usuario $usuario
 * @property Collection|AporteAhorro[] $aporte_ahorros
 *
 * @package App\Models
 */
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
		'fecha_meta' => 'datetime',
		'cantidad_cuotas' => 'int'
	];

	protected $fillable = [
		'usuario_id',
		'concepto',
		'descripcion',
		'monto_meta',
		'total_acumulado',
		'frecuencia',
		'fecha_creacion',
		'fecha_meta',
		'estado',
		'cantidad_cuotas'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class);
	}

	public function aporte_ahorros()
	{
		return $this->hasMany(AporteAhorro::class, 'ahorro_meta_id');
	}
}
