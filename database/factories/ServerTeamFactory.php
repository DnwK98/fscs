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

$factory->define(\App\ServerTeam::class, function (Faker $faker) {
    return [
        'team_number' => rand(1,2),
        'name' => $faker->city,
        'tag' => Str::random(4),
    ];
});
$factory->afterCreating(\App\ServerTeam::class, function (\App\ServerTeam $serverTeam, Faker $faker) {
    factory(App\TeamPlayer::class, 5)->create([
        'team_id' => $serverTeam->id
    ]);
});
