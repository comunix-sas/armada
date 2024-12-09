<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PreContractual extends Model
{
    use HasFactory;

    protected $table = 'sgc_precontractual';
    protected $primaryKey = 'idPrecontractual';

    protected $fillable = [
        'plan_adquisicion_id',
        'titulo',
        'descripcion',
        'estudio_previo_path',
        'estado_estudio_previo',
        'fecha_aprobacion_estudio',
        'requerimiento_inicio',
        'fecha_notificacion',
        'proceso_contratacion_id',
        'secop_estado',
        'fecha_publicacion',
        'fecha_recepcion_ofertas',
        'estado_proceso',
        'documento_adjudicacion',
        'created_by',
        'updated_by'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'fecha_aprobacion_estudio',
        'fecha_notificacion',
        'fecha_publicacion',
        'fecha_recepcion_ofertas'
    ];

    public function planAdquisicion()
    {
        return $this->belongsTo(PlanAdquisicion::class, 'plan_adquisicion_id', 'idPlan');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::user()->id;
                $model->updated_by = Auth::user()->id;
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::user()->id;
            }
        });
    }
}
