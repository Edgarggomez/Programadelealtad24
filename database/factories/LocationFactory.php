<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Location;
use App\TiendaCC;
use App\BdCC;
use Faker\Generator as Faker;

$factory->define(Location::class, function (Faker $faker) {
    return [
        'ubicacion' => $faker->words(3, true),
        'id_tda' => $faker->randomElement(TiendaCC::pluck('id_tda')),
        'id_bd' => $faker->randomElement(BdCC::pluck('id_bd')),
        'estatus' => '1'
    ];
});
