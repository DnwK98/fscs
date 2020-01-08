<?php

use App\Get5StatsMatch;
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

$factory->define(Get5StatsMatch::class, function (Faker $faker) {
    return [
        'matchid' => $faker->unique()->randomNumber(9),
        'start_time' => new DateTime('-3 hour'),
        'end_time' => new DateTime('-1 hour'),
        'winner' => "team" . rand(1, 2),
        'series_type' => "bo" . rand(1, 3),
        'team1_name' => $faker->city,
        'team1_score' => rand(0, 3),
        'team2_name' => $faker->city,
        'team2_score' => rand(0, 3),
    ];
});