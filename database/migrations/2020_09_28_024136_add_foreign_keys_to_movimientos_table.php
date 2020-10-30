<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimientos_saldo', function (Blueprint $table) {
            $table->foreign('id_ubicacion')
                ->references('id_ubicacion')
                ->on('ubicaciones')
                ->onDelete('cascade');

            $table->foreign('id_cliente')
                ->references('id_cliente')
                ->on('clientes')
                ->onDelete('cascade');

            $table->foreign('id_tarjeta')
                ->references('id_tarjeta')
                ->on('tarjetas')
                ->onDelete('cascade');

            $table->foreign('id_consumo')
                ->references('id_consumo')
                ->on('consumos')
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
        Schema::table('movimientos_saldo', function (Blueprint $table) {
            $table->dropForeign(['id_ubicacion']);
            $table->dropForeign(['id_cliente']);
            $table->dropForeign(['id_tarjeta']);
            $table->dropForeign(['id_consumo']);
        });
    }
}
