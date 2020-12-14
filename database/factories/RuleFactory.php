<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Location;
use App\Regla;
use Faker\Generator as Faker;

$factory->define(Regla::class, function (Faker $faker) {
    $dias = $faker->randomElements(array(0, 1, 2, 3, 4, 5, 6), $faker->numberBetween(1, 7));
    sort($dias, SORT_NUMERIC);
    return [
        'regla' => 'NA',
        'tipo' => '0',
        'monto' => 0,
        'estatus' => 1,
        'porcentaje' => $faker->numberBetween(1, 99),
        'hora_inicial' => $faker->numberBetween(0, 11),
        'hora_final' => $faker->numberBetween(12, 23),
        'dias' => $dias
    ];
});
