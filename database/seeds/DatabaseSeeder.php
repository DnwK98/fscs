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
}
