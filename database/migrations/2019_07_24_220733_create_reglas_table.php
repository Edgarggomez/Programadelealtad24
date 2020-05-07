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
            $table->integer('tipo');
            $table->boolean('estatus');
            $table->decimal('monto',12,2);
            $table->integer('porcentaje');
            $table->integer('hora_inicial');
            $table->integer('hora_final');
            $table->boolean('lunes')->default(0);
            $table->boolean('martes')->default(0);
            $table->boolean('miercoles')->default(0);
            $table->boolean('jueves')->default(0);
            $table->boolean('viernes')->default(0);
            $table->boolean('sabado')->default(0);
            $table->boolean('domingo')->default(0);
            $table->integer('id_ubicacion');
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
        Schema::dropIfExists('reglas');
    }
}
