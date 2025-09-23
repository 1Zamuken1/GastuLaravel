<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Egreso
 * 
 * @property int $egreso_id
 * @property string|null $tipo
 * @property float $monto
 * @property string|null $descripcion
 * @property Carbon $fecha_registro
 * @property int|null $concepto_egreso_id
 * @property int $usuario_id
 * 
 * @property Usuario $usuario
 * @property ConceptoIngreso|null $concepto_egreso
 *
 * @package App\Models
 */
class Egreso extends Model
{
	protected $table = 'egreso';
	protected $primaryKey = 'egreso_id';
	public $timestamps = false;

	protected $casts = [
		'monto' => 'float',
		'fecha_registro' => 'datetime',
		'concepto_egreso_id' => 'int',
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'tipo',
		'monto',
		'descripcion',
		'fecha_registro',
		'concepto_egreso_id',
		'usuario_id'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'usuario_id');
	}

	public function conceptoEgreso()
	{
		return $this->belongsTo(ConceptoEgreso::class, 'concepto_egreso_id');
	}
}
