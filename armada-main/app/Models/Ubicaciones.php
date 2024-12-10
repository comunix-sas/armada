<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicaciones extends Model
{
  protected $table = 'Sgc_ubicaciones';
  protected $fillable = ['codigo', 'ubicacion'];
}
