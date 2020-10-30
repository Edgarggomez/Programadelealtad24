<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReglasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reglas', function (Blueprint $table) {
            $table->increments('id_regla');
            $table->string('regla',50);
            $table->boolean('tipo')->default(0);
            $table->boolean('estatus');
            $table->decimal('monto',12,2);
            $table->integer('porcentaje');
            $table->integer('hora_inicial');
            $table->integer('hora_final');
            $table->string('dias');
            $table->integer('id_ubicacion')->unsigned();
            $table->timestamps();

            $table->foreign('id_ubicacion')
                ->references('id_ubicacion')
                ->on('ubicaciones')
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
        Schema::dropIfExists('reglas');
    }
}
