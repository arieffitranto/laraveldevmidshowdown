<?php

namespace App\Console\Commands;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Console\Command;

class UpdateUserAttributes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-user-attributes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update User description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //return 0;
        $faker = Faker::create();
        $users = User::all();
        foreach ($users as $user) {
            $user->firstname = $faker->firstName();
            $user->lastname = $faker->lastName();
            $timezones = ['CET', 'CST', 'GMT+1'];
            $user->timezone = $timezones[array_rand($timezones)];
            $user->save();
            $this->info("Updated user: " . $user->email);
        }
    }
}
