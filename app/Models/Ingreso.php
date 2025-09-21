<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ingreso
 * 
 * @property int $ingreso_id
 * @property string|null $tipo
 * @property float $monto
 * @property string|null $descripcion
 * @property Carbon $fecha_registro
 * @property int|null $concepto_ingreso_id
 * @property int $usuario_id
 * 
 * @property Usuario $usuario
 * @property ConceptoIngreso|null $concepto_ingreso
 *
 * @package App\Models
 */
class Ingreso extends Model
{
	protected $table = 'ingreso';
	protected $primaryKey = 'ingreso_id';
	public $timestamps = false;

	protected $casts = [
		'monto' => 'float',
		'fecha_registro' => 'datetime',
		'concepto_ingreso_id' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'tipo',
		'monto',
		'descripcion',
		'fecha_registro',
		'concepto_ingreso_id',
		'usuario_id'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'usuario_id');
	}

	public function conceptoIngreso()
	{
		return $this->belongsTo(ConceptoIngreso::class, 'concepto_ingreso_id');
	}
}
