<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProyeccionEgreso
 * 
 * @property int $proyeccion_egreso_id
 * @property float $monto_programado
 * @property string $descripcion
 * @property Carbon|null $fecha_fin
 * @property bool|null $activo
 * @property Carbon $fecha_creacion
 * @property int $concepto_egreso_id
 * 
 * @property ConceptoEgreso $concepto_egreso
 *
 * @package App\Models
 */
class ProyeccionEgreso extends Model
{
	protected $table = 'proyeccion_egreso';
	protected $primaryKey = 'proyeccion_egreso_id';
	public $timestamps = false;

	protected $casts = [
		'monto_programado' => 'float',
		'fecha_fin' => 'datetime',
		'activo' => 'bool',
		'fecha_creacion' => 'datetime',
		'concepto_egreso_id' => 'int'
	];

	protected $fillable = [
		'monto_programado',
		'descripcion',
		'fecha_fin',
		'activo',
		'fecha_creacion',
		'concepto_egreso_id'
	];

	public function concepto_egreso()
	{
		return $this->belongsTo(ConceptoEgreso::class);
	}
}
