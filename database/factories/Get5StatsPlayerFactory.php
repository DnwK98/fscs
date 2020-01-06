<?php

use App\Get5StatsPlayer;
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

$factory->define(Get5StatsPlayer::class, function (Faker $faker) {
    return [
        'matchid' => $faker->unique()->randomNumber(9),
        'mapnumber' => rand(0, 5),
        'steamid64' => $faker->regexify('[0-9]{17}'),
        'team' => "team" . rand(1,2),
        'rounds_played' => 16,
        'name' => $faker->firstName,
        'kills' => rand(0, 15),
        'deaths' => rand(0, 15),
        'assists' => rand(0, 15),
        'flashbang_assists' => rand(0, 15),
        'teamkills' => rand(0, 3),
        'headshot_kills' => rand(0, 15),
        'damage' => rand(500, 5000),
        'bomb_plants' => rand(0, 5),
        'bomb_defuses' => rand(0, 5),
        'v1' => rand(0, 10),
        'v2' => rand(0, 10),
        'v3' => rand(0, 5),
        'v4' => rand(0, 5),
        'v5' => rand(0, 3),
        '2k' => rand(0, 10),
        '3k' => rand(0, 5),
        '4k' => rand(0, 2),
        '5k' => rand(0, 2),
        'firstkill_t' => rand(0, 5),
        'firstkill_ct' => rand(0, 5),
        'firstdeath_t' => rand(0, 5),
        'firstdeath_ct' => rand(0, 5),
    ];
});