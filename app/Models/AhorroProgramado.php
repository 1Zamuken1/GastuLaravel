<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AhorroProgramado
 * 
 * @property int $ahorro_programado_id
 * @property int $ahorro_meta_id
 * @property float $monto_programado
 * @property string $frecuencia
 * @property Carbon $fecha_inicio
 * @property Carbon|null $fecha_fin
 * @property int|null $num_cuotas
 * @property Carbon|null $ultimo_aporte_generado
 * 
 * @property AhorroMeta $ahorro_meta
 *
 * @package App\Models
 */
class AhorroProgramado extends Model
{
	protected $table = 'ahorro_programado';
	protected $primaryKey = 'ahorro_programado_id';
	public $timestamps = false;

	protected $casts = [
		'ahorro_meta_id' => 'int',
		'monto_programado' => 'float',
		'fecha_inicio' => 'datetime',
		'fecha_fin' => 'datetime',
		'num_cuotas' => 'int',
		'ultimo_aporte_generado' => 'datetime'
	];

	protected $fillable = [
		'ahorro_meta_id',
		'monto_programado',
		'frecuencia',
		'fecha_inicio',
		'fecha_fin',
		'num_cuotas',
		'ultimo_aporte_generado'
	];

	public function ahorro_meta()
	{
		return $this->belongsTo(AhorroMeta::class, 'ahorro_meta_id');
	}
}
