<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreContractual extends Model
{
    use HasFactory;

    protected $table = 'pre_contractuals';

    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'plan_adquisicion_id',
        'estudio_previo',
        'estado_estudio_previo', // pendiente, en_revision, aprobado, rechazado
        'fecha_aprobacion_estudio',
        'requerimiento_inicio',
        'fecha_notificacion',
        'proceso_contratacion_id',
        'secop_estado',
        'fecha_publicacion',
        'fecha_recepcion_ofertas',
        'estado_proceso', // en_curso, adjudicado, cancelado, etc
        'documento_adjudicacion',
        'created_by',
        'updated_by'
    ];

    // Relaciones
    public function planAdquisicion()
    {
        return $this->belongsTo(PlanAdquisicion::class);
    }

    public function historialCambios()
    {
        return $this->hasMany(PreContractualHistorial::class);
    }
}
