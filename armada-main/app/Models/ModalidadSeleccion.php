<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadSeleccion extends Model
{
  protected $table = 'Sgc_modalidades_seleccion';
  protected $fillable = ['codigo', 'descripcion'];
}
