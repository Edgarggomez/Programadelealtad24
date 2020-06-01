<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id_cliente');
            $table->integer('id_memebresia')->nullable();
            $table->bigInteger('id_tarjeta_principal')->unsigned()->nullable();
            $table->integer('id_ubicacion')->unsigned();
            $table->string('nombre');
            $table->string('domicilio',255)->nullable();
            $table->string('colonia',100)->nullable();
            $table->string('cp',10)->nullable();
            $table->integer('id_ciudad')->nullable();
            $table->integer('id_estado')->nullable();
            $table->boolean('persona_fisica')->nullable();
            $table->string('rfc',16);
            $table->string('correo')->unique();
            $table->string('celular',32);
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['M', 'F'])->default('M');
            $table->boolean('flotilla')->default(0);
            $table->enum('estatus', [1, 0, 2])->default(1);
            $table->decimal('saldo',12,2)->nullable();
            $table->text('notas')->nullable();
            $table->dateTime('ultimo_consumo_cg')->nullable();
            $table->dateTime('ultimo_consumo_cc')->nullable();
            $table->dateTime('fecha_sync_saldo')->nullable();
            $table->dateTime('fecha_actualizacion_saldo')->nullable();
            $table->dateTime('fecha_creacion')->nullable(); //TODO: estas dos fechas me parecen redudante, verificar si se pueden elimiar
            $table->dateTime('fecha_actualizacion')->nullable();
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
        Schema::dropIfExists('clientes');
    }
}
