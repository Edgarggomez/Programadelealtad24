<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BdCC;
use Faker\Generator as Faker;

$factory->define(BdCC::class, function (Faker $faker) {
    return [
        'nombre' => $faker->words(3, true),
        'bd' => $faker->words(2, true),
        'estatus' => '1',
        'last_message' => $faker->randomElement(['SUCCESS', 'FAILED', 'ERROR']),
        'ultima_conexion' => $faker->dateTime(),
        'fecha_actualizacion' => $faker->dateTime(),
        'fecha_sync_bd' => $faker->dateTime()
    ];
});
