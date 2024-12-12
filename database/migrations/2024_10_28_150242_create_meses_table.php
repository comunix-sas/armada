<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('meses', function (Blueprint $table) {
            $table->id();
            $table->integer('mes'); // Columna para almacenar el número del mes
            $table->string('nombre'); // Columna para almacenar el nombre del mes
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('meses');
    }
};
