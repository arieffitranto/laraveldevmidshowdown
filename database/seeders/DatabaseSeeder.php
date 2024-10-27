<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::factory(20)->create()->each(function ($user) {
            $timezones = ['CET', 'CST', 'GMT+1'];
            $user->timezone = $timezones[array_rand($timezones)];
            $user->save();
        });
    }
}
