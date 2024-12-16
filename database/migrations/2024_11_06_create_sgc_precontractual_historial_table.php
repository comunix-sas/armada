<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sgc_precontractual_historial', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('precontractual_id');
            $table->string('tipo_cambio');
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('usuario_id');
            $table->timestamp('fecha_cambio');
            $table->timestamps();

            $table->foreign('precontractual_id')
                  ->references('idPrecontractual')
                  ->on('sgc_precontractual')
                  ->onDelete('cascade');

            $table->foreign('usuario_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('sgc_precontractual_historial');
    }
}; 
