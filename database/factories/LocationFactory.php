<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Location;
use App\BdCC;
use App\TiendaCC;
use Faker\Generator as Faker;

$factory->define(Location::class, function (Faker $faker) {
    return [
        'ubicacion' => substr($faker->unique()->words(3, true), 0, 30),
        'id_tda' => (TiendaCC::whereNotIn('id_tda', Location::pluck('id_tda'))->first()) ? $faker->randomElement(TiendaCC::pluck('id_tda')) : factory(TiendaCC::class)->create()->id_tda,
        'id_bd' =>  (BdCC::first()) ? $faker->randomElement(BdCC::pluck('id_bd')) : factory(BdCC::class)->create()->id_bd,
        'estatus' => '1'
    ];
});
