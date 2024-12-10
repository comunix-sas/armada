<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{
    use HasFactory;

    protected $table = 'sgc_meses';
    protected $fillable = ['mes', 'nombre'];
    public $timestamps = false;
}
