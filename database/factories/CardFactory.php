<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'tarjeta' => $faker->regexify('[0-9]{15}'),
        'nombre' => $faker->name(),
        'adicional' => $faker->boolean(),
        'estatus' => '1'
    ];
});
