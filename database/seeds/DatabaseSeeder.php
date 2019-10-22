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
        $this->call([
            TrainsTableSeeder::class,
            DirectionsTableSeeder::class,
            StationsTableSeeder::class,
            SchedulesTableSeeder::class,
            FeedSchedulesTableSeeder::class,
        ]);
    }
}
