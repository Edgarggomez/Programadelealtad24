<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('12345678'),
        'status' => '1'
    ];
});

$factory->afterCreatingState(User::class, 'admin', function ($user) {
    $user->assignRole('admin');
});

$factory->afterCreatingState(User::class, 'gerente', function ($user) {
    $user->assignRole('gerente');
});

$factory->afterCreatingState(User::class, 'operador', function ($user) {
    $user->assignRole('operador');
});
