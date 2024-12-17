<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('sgc_plan_adquisicion', function (Blueprint $table) {
      // Primary Key
      $table->id('idPlan');

      // Campos de la tabla
      $table->string('nombrePlan');
      $table->string('version');
      $table->integer('modalidadPago');
      $table->decimal('trm', 10, 2);
      $table->decimal('presupuesto', 15, 2);
      $table->string('currency');
      $table->integer('cdp')->nullable();
      $table->decimal('conversion', 15, 2);
      $table->integer('rp');
      $table->integer('duracionContrato');
      $table->integer('tipoDuracion');
      $table->string('fuenteRecursos');
      $table->boolean('vigencia');
      $table->boolean('estado');
      $table->integer('unidadContratacion');
      $table->string('nombreResponsable');
      $table->string('telefonoResponsable');
      $table->string('emailResponsable');
      $table->string('notasAdicionales')->nullable();

      // Relaciones - Foreign Keys
      $table->unsignedBigInteger('mes_id');
      $table->foreign('mes_id')
        ->references('id')
        ->on('sgc_meses')
        ->onDelete('cascade');

      $table->unsignedBigInteger('modalidad_seleccion_id');
      $table->foreign('modalidad_seleccion_id')
        ->references('id')
        ->on('sgc_modalidades_seleccion')
        ->onDelete('cascade');

      $table->unsignedBigInteger('ubicacion_id');
      $table->foreign('ubicacion_id')
        ->references('id')
        ->on('sgc_ubicaciones')
        ->onDelete('cascade');

      // Timestamps
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('sgc_plan_adquisicion');
  }
};
