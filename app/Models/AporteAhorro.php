<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AporteAhorro
 * 
 * @property int $aporte_ahorro_id
 * @property int $ahorro_meta_id
 * @property float $monto
 * @property Carbon $fecha_registro
 * 
 * @property AhorroMetum $ahorro_metum
 *
 * @package App\Models
 */
class AporteAhorro extends Model
{
	protected $table = 'aporte_ahorro';
	protected $primaryKey = 'aporte_ahorro_id';
	public $timestamps = false;

	protected $casts = [
		'ahorro_meta_id' => 'int',
		'monto' => 'float',
		'fecha_registro' => 'datetime'
	];

	protected $fillable = [
		'ahorro_meta_id',
		'monto',
		'fecha_registro'
	];

	public function ahorro_metum()
	{
		return $this->belongsTo(AhorroMetum::class, 'ahorro_meta_id');
	}
}
