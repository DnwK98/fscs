<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function seedProduction()
    {
    }

    public function seedDev()
    {
        $this->seedUsers();
        $this->seedGet5();
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->seedProduction();

        if (app()->environment() !== 'production') {
            $this->seedDev();
        }
    }

    public function seedUsers()
    {
        factory(App\User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@fscs.pl',
            'password' => Hash::make('password')
        ]);

        factory(App\User::class, 20)->create();
    }

    public function seedGet5()
    {
        factory(App\Get5StatsMatch::class, 50)->create();

        /** @var \App\Get5StatsMatch $match */
        foreach (App\Get5StatsMatch::query()->getModels() as $match){
            $mapNumber = (int)str_replace("bo", '', $match->series_type);
            for($i = 0; $i < $mapNumber; ++$i){
                factory(App\Get5StatsMap::class)->create([
                    'matchid' => $match->matchid,
                    'mapnumber' => $i
                ]);
            }
        }

        /** @var \App\Get5StatsMap $map */
        foreach (App\Get5StatsMap::query()->getModels() as $map){
            factory(App\Get5StatsPlayer::class, 10)->create([
                'matchid' => $map->matchid,
                'mapnumber' => $map->mapnumber,
            ]);
        }
    }
}
