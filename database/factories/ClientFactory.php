<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cliente;
use App\Location;
use App\Tarjeta;
use App\TarjetaCC;
use Faker\Generator as Faker;

$factory->define(Cliente::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name(),
        'celular' => $faker->regexify('[0-9]{11}'),
        'correo' => $faker->unique()->safeEmail,
        'sexo' => $faker->randomElement(array ('M', 'F')),
        'flotilla' => $faker->boolean(),
        'rfc' => $faker->regexify('[A-Z]{4}[0-9]{6}[A-Z0-9]{3}'),
        'estatus' => '1',
        'id_ubicacion' => $faker->randomElement(Location::pluck('id_ubicacion'))
    ];
});


$factory->afterCreatingState(Cliente::class, 'tarjeta', function ($client) {
    $tarjetaCC = factory(TarjetaCC::class)->create();
    $input = ['tarjeta' => $tarjetaCC->tarjeta, 'adicional' => false, 'id_cliente' => $client->id_cliente, 'nombre' => $client->nombre];
    $card = Tarjeta::createOrUpdate($input);
    $client->id_tarjeta_principal = $card->id_tarjeta;
    $client->save();
});
