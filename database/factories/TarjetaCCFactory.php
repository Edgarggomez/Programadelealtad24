<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TarjetaCC;
use Faker\Generator as Faker;

$factory->define(TarjetaCC::class, function (Faker $faker) {
    return [
        'tarjeta' => $faker->regexify('[0-9]{15}'),
        'fecha_sync_nueva_tarjeta' => $faker->dateTime()
    ];
});
