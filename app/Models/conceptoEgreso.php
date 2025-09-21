<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ConceptoEgreso
 * 
 * @property int $concepto_egreso_id
 * @property string $nombre
 * @property string $descripcion
 * 
 * @property Collection|Egreso[] $egresos
 * @property Collection|ProyeccionEgreso[] $proyeccion_egresos
 *
 * @package App\Models
 */
class ConceptoEgreso extends Model
{
	protected $table = 'concepto_egreso';
	protected $primaryKey = 'concepto_egreso_id';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'descripcion'
	];

	public function egresos()
	{
		return $this->hasMany(Egreso::class);
	}

	public function proyeccion_egresos()
	{
		return $this->hasMany(ProyeccionEgreso::class);
	}
}
