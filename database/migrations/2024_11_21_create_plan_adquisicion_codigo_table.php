<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
      Schema::create('plan_adquisicion_codigo', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('plan_adquisicion_id');
        $table->unsignedBigInteger('codigo_unspsc_id');

        $table->foreign('plan_adquisicion_id')
              ->references('idPlan')
              ->on('sgc_plan_adquisicion')
              ->onDelete('cascade');

        $table->foreign('codigo_unspsc_id')
              ->references('id')
              ->on('Sgc_codigo_unspsc')
              ->onDelete('cascade');

        $table->timestamps();
    });
    }

    public function down()
    {
        Schema::dropIfExists('plan_adquisicion_codigo');
    }
};
