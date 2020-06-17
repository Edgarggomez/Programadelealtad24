<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'ubicacion' => $faker->words($nb = 3, $asText = true),
        'estatus' => '1'
    ];
});
