<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sgc_precontractual_historial', function (Blueprint $table) {
            $table->id('idHistorial');

            // Relación con precontractual
            $table->unsignedBigInteger('precontractual_id');
            $table->foreign('precontractual_id')
                  ->references('idPrecontractual')
                  ->on('sgc_precontractual')
                  ->onDelete('cascade');

            $table->string('tipo_cambio');
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('usuario_id');
            $table->timestamp('fecha_cambio');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sgc_precontractual_historial');
    }
}; 
