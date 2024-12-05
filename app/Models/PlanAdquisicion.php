<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanAdquisicion extends Model
{
  use HasFactory;

  protected $table = 'sgc_plan_adquisicion';
  protected $primaryKey = 'idPlan';

  protected $fillable = [
    'nombrePlan',
    'version',
    'modalidadPago',
    'presupuesto',
    'currency',
    'trm',
    'cdp',
    'conversion',
    'rp',
    'modalidad_seleccion_id',
    'mes_id',
    'duracionContrato',
    'tipoDuracion',
    'fuenteRecursos',
    'vigencia',
    'estado',
    'unidadContratacion',
    'ubicacion_id',
    'nombreResponsable',
    'telefonoResponsable',
    'emailResponsable',
    'notasAdicionales'
  ];

  // Relaciones
  public function modalidadSeleccion()
  {
    return $this->belongsTo(ModalidadSeleccion::class, 'modalidad_seleccion_id');
  }

  public function codigoUnspsc()
  {
    return $this->belongsTo(CodigoUnspsc::class, 'codigo_unspsc_id');
  }

  public function ubicacion()
  {
    return $this->belongsTo(Ubicaciones::class, 'ubicacion_id');
  }

  public function mes()
  {
    return $this->belongsTo(Mes::class, 'mes_id');
  }

  public function codigosUnspsc()
  {
    return $this->belongsToMany(CodigoUnspsc::class, 'plan_adquisicion_codigo', 'plan_adquisicion_id', 'codigo_unspsc_id');
  }

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($planAdquisicion) {
        if (!$planAdquisicion->version) {
            $planAdquisicion->version = static::max('version') + 1;
        }
    });
  }

}
