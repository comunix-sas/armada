<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreContractualHistorial extends Model
{
    protected $fillable = [
        'pre_contractual_id',
        'tipo_cambio',
        'estado_anterior',
        'estado_nuevo',
        'comentarios',
        'usuario_id',
        'fecha_cambio'
    ];

    public function preContractual()
    {
        return $this->belongsTo(PreContractual::class);
    }
} 
