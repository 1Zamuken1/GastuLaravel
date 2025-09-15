<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ConceptoIngreso
 * 
 * @property int $concepto_ingreso_id
 * @property string $nombre
 * @property string $descripcion
 * @property int $usuario_id
 * 
 * @property Usuario $usuario
 * @property Collection|Ingreso[] $ingresos
 * @property Collection|ProyeccionIngreso[] $proyeccion_ingresos
 *
 * @package App\Models
 */
class ConceptoIngreso extends Model
{
	protected $table = 'concepto_ingreso';
	protected $primaryKey = 'concepto_ingreso_id';
	public $timestamps = false;

	protected $casts = [
		'usuario_id' => 'int'
	];

	protected $fillable = [
		'nombre',
		'descripcion',
		'usuario_id'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class);
	}

	public function ingresos()
	{
		return $this->hasMany(Ingreso::class);
	}

	public function proyeccion_ingresos()
	{
		return $this->hasMany(ProyeccionIngreso::class);
	}
}
