<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProyeccionIngreso
 * 
 * @property int $proyeccion_ingreso_id
 * @property float $monto_programado
 * @property string $descripcion
 * @property Carbon|null $fecha_fin
 * @property bool|null $activo
 * @property Carbon $fecha_creacion
 * @property int $concepto_ingreso_id
 * @property int $usuario_id
 * 
 * @property Usuario $usuario
 * @property ConceptoIngreso $concepto_ingreso
 *
 * @package App\Models
 */
class ProyeccionIngreso extends Model
{
	protected $table = 'proyeccion_ingreso';
	protected $primaryKey = 'proyeccion_ingreso_id';
	public $timestamps = false;

	protected $casts = [
		'monto_programado' => 'float',
		'fecha_fin' => 'datetime',
		'activo' => 'bool',
		'fecha_creacion' => 'datetime',
		'concepto_ingreso_id' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'monto_programado',
		'descripcion',
		'fecha_fin',
		'activo',
		'fecha_creacion',
		'concepto_ingreso_id',
		'usuario_id'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class);
	}

	public function conceptoIngreso()
	{
		return $this->belongsTo(ConceptoIngreso::class);
	}
}
