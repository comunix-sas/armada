<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSgcModalidadesSeleccionTable extends Migration
{
    public function up()
    {
        Schema::create('Sgc_modalidades_seleccion', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 100)->unique();
            $table->string('descripcion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('Sgc_modalidades_seleccion');
    }
}
