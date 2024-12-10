<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sgc_precontractual', function (Blueprint $table) {
            $table->id('idPrecontractual');

            // Relación con plan de adquisición
            $table->unsignedBigInteger('plan_adquisicion_id');
            $table->foreign('plan_adquisicion_id')
                  ->references('idPlan')
                  ->on('sgc_plan_adquisicion')
                  ->onDelete('cascade');

            // Campos para estudios previos
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('estudio_previo_path')->nullable(); // Ruta del documento
            $table->enum('estado_estudio_previo', [
                'pendiente',
                'en_revision',
                'aprobado',
                'rechazado'
            ])->default('pendiente');
            $table->timestamp('fecha_aprobacion_estudio')->nullable();

            // Campos para proceso de contratación
            $table->string('requerimiento_inicio')->nullable();
            $table->timestamp('fecha_notificacion')->nullable();
            $table->string('proceso_contratacion_id')->nullable();

            // Campos SECOP
            $table->enum('secop_estado', [
                'pendiente',
                'publicado',
                'en_proceso',
                'finalizado'
            ])->default('pendiente');
            $table->timestamp('fecha_publicacion_secop')->nullable();
            $table->timestamp('fecha_recepcion_ofertas')->nullable();

            // Control del proceso
            $table->enum('estado_proceso', [
                'en_curso',
                'adjudicado',
                'cancelado',
                'desierto'
            ])->default('en_curso');

            // Documentos y adjudicación
            $table->string('documento_adjudicacion_path')->nullable();
            $table->decimal('valor_adjudicacion', 15, 2)->nullable();
            $table->string('proveedor_adjudicado')->nullable();

            // Auditoría
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Para mantener historial

            // Índices
            $table->index('estado_estudio_previo');
            $table->index('estado_proceso');
            $table->index('secop_estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sgc_precontractual');
    }
}; 
