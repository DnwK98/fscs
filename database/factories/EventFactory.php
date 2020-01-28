<?php

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

$factory->define(\App\EventEntity::class, function (Faker $faker) {
    return [
        'matchid' => $faker->unique()->randomNumber(9),
        'mapnumber' => rand(0, 5),
        'start_time' => new DateTime('-3 hour'),
        'end_time' => new DateTime('-1 hour'),
        'winner' => "team" . rand(1,2),
        'mapname' => "de_mirage",
        'team1_score' => rand(0, 3),
        'team2_score' => rand(0, 3),
    ];
});
