<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->seedUsers();
    }

    public function seedUsers()
    {
        factory(App\User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@fscs.pl',
            'password' => Hash::make('password')
        ]);
        factory(App\User::class, 50)->create();
    }
}
