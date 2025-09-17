<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class Usuario
 * 
 * @property int $usuario_id
 * @property string $nombre
 * @property string $correo
 * @property string|null $telefono
 * @property string $password
 * @property Carbon $fecha_registro
 * @property bool|null $activo
 * @property int $rol_id
 * 
 * @property Rol $rol
 * @property Collection|AhorroMetum[] $ahorro_meta
 * @property Collection|ConceptoEgreso[] $concepto_egresos
 * @property Collection|ConceptoIngreso[] $concepto_ingresos
 *
 * @package App\Models
 */
class Usuario extends Authenticatable
{
	use Notifiable;

	protected $table = 'usuario';
	protected $primaryKey = 'usuario_id';
	public $timestamps = false;

	protected $casts = [
		'fecha_registro' => 'datetime',
		'activo' => 'bool',
		'rol_id' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'nombre',
		'correo',
		'telefono',
		'password',
		'fecha_registro',
		'activo',
		'rol_id'
	];

	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = bcrypt($value);
	}

	public function getAuthIdentifierName()
	{
		return 'correo';
	}

	public function rol()
	{
		return $this->belongsTo(Rol::class);
	}

	public function ahorro_meta()
	{
		return $this->hasMany(AhorroMetum::class);
	}

	public function concepto_egresos()
	{
		return $this->hasMany(ConceptoEgreso::class);
	}

	public function concepto_ingresos()
	{
		return $this->hasMany(ConceptoIngreso::class);
	}
}
