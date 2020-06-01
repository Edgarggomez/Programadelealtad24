<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarjetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarjetas', function (Blueprint $table) {
            $table->bigIncrements('id_tarjeta')->unique();
            $table->bigInteger('id_cliente')->unsigned();
            $table->string('tarjeta',32)->unique();
            $table->string('nombre',255);
            $table->boolean('adicional');
            $table->boolean('estatus');
            $table->decimal('saldo_migracion',12,2)->nullable();
            $table->dateTime('fecha_sync_update_tarjeta')->nullable();
            $table->dateTime('fecha_sync_por_migrar')->nullable();
            $table->dateTime('fecha_sync_saldo_inicial')->nullable();
            $table->dateTime('fecha_creacion')->nullable(); //TODO: verificar sin son necesarias estas columnas
            $table->dateTime('fecha_actualizacion')->nullable();
            $table->timestamps();

            $table->foreign('id_cliente')
                ->references('id_cliente')
                ->on('clientes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarjetas');
    }
}
