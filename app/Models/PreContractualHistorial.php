<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreContractualHistorial extends Model
{
    protected $table = 'sgc_precontractual_historial';
    protected $primaryKey = 'idHistorial';

    protected $fillable = [
        'precontractual_id',
        'tipo_cambio',
        'estado_nuevo',
        'comentarios',
        'usuario_id',
        'fecha_cambio'
    ];

    public function preContractual()
    {
        return $this->belongsTo(PreContractual::class, 'precontractual_id', 'idPrecontractual');
    }
}
