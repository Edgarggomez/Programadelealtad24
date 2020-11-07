<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBdsCcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bds_cc', function (Blueprint $table) {
           $table->increments('id_bd');
            $table->string('nombre',128);
            $table->string('bd',128);
            $table->boolean('estatus');
            $table->string('last_message',255)->nullable();
            $table->dateTime('ultima_conexion')->nullable();
            $table->dateTime('fecha_actualizacion')->useCurrent();
            $table->dateTime('fecha_sync_bd')->useCurrent();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bds_cc');
    }
}
