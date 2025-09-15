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
 * @property float|null $monto_meta
 * @property float|null $total_acumulado
 * @property Carbon $fecha_creacion
 * @property Carbon|null $fecha_meta
 * @property bool|null $activa
 * 
 * @property Usuario $usuario
 * @property Collection|AhorroProgramado[] $ahorro_programados
 * @property Collection|AporteAhorro[] $aporte_ahorros
 *
 * @package App\Models
 */
class AhorroMetum extends Model
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
		'activa' => 'bool'
	];

	protected $fillable = [
		'usuario_id',
		'concepto',
		'descripcion',
		'monto_meta',
		'total_acumulado',
		'fecha_creacion',
		'fecha_meta',
		'activa'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class);
	}

	public function ahorro_programados()
	{
		return $this->hasMany(AhorroProgramado::class, 'ahorro_meta_id');
	}

	public function aporte_ahorros()
	{
		return $this->hasMany(AporteAhorro::class, 'ahorro_meta_id');
	}
}
