<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TiendaCC;
use Faker\Generator as Faker;

$factory->define(TiendaCC::class, function (Faker $faker) {
    return [
        'id_tda' => $faker->unique()->numberBetween(0, 99999),
        'nombre' => $faker->words(3, true),
        'fecha_sync_establecimiento' => $faker->dateTime()
    ];
});
