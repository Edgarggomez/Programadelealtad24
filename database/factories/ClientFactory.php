<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cliente;
use Faker\Generator as Faker;

$factory->define(Cliente::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name(),
        'celular' => $faker->phoneNumber(),
        'correo' => $faker->unique()->safeEmail,
        'sexo' => $faker->randomElement(array ('M', 'F')),
        'flotilla' => $faker->boolean(),
        'rfc' => $faker->regexify('[A-Z]{4}[0-9]{6}[A-Z0-9]{3}'),
        'estatus' => $faker->randomElement(array ('0', '1', '2')),
        'id_ubicacion' => factory(App\Location::class)
    ];
});
