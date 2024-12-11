<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModalidadesSeleccionTable extends Migration
{
    public function up()
    {
        Schema::create('modalidades_seleccion', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 100)->unique();
            $table->string('descripcion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('modalidades_seleccion');
    }
}
