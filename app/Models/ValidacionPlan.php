<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidacionPlan extends Model
{
    use HasFactory;

    protected $table = 'validacion_planes';

    protected $fillable = [
        'plan_adquisicion_id',
        'estado',
        'observaciones',
        'usuario_id',
        'fecha_validacion'
    ];

    public function planAdquisicion()
    {
        return $this->belongsTo(PlanAdquisicion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
} 
