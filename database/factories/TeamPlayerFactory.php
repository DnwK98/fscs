<?php

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

$factory->define(\App\TeamPlayer::class, function (Faker $faker) {
    return [
        // id autoincrement
        'steam_id_64' => $faker->regexify('[0-9]{17}'),
        'name' => $faker->firstName
    ];
});