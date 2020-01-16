<?php

use App\Server;
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

$factory->define(Server::class, function (Faker $faker) {
    return [
        'match_id' => $faker->unique()->regexify('[0-9]{7}'),
        'map' => \App\Enums\MapEnum::Random(),
        'status' => \App\Enums\ServerStatusEnum::Random(),
        'port' => rand(27015, 27300)
    ];
});
$factory->afterCreating(Server::class, function (Server $server, Faker $faker) {
    echo ".";
    factory(App\ServerTeam::class)->create([
        'team_number' => 1,
        'server_id' => $server->id
    ]);
    factory(App\ServerTeam::class)->create([
        'team_number' => 2,
        'server_id' => $server->id
    ]);
});
