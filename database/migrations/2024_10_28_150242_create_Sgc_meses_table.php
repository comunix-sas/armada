<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('Sgc_meses', function (Blueprint $table) {
            $table->id();
            $table->integer('mes');
            $table->string('nombre');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('Sgc_meses');
    }
};
