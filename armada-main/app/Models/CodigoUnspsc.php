<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodigoUnspsc extends Model
{
  protected $table = 'Sgc_codigo_unspsc';
  protected $fillable = ['codigo', 'descripcion'];

  public function planesAdquisicion()
  {
    return $this->belongsToMany(PlanAdquisicion::class, 'plan_adquisicion_codigo');
  }
}
