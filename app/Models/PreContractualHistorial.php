<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreContractualHistorial extends Model
{
    protected $table = 'sgc_precontractual_historial';
    
    protected $guarded = [];

    /**
     * Obtiene el usuario que realizó el cambio
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    /**
     * Obtiene el precontractual asociado
     */
    public function precontractual()
    {
        return $this->belongsTo(PreContractual::class, 'precontractual_id', 'idPrecontractual');
    }
}
