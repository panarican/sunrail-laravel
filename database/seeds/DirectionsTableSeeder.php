<?php

use App\Direction;
use Goutte\Client;
use Illuminate\Database\Seeder;
use Symfony\Component\DomCrawler\Crawler;

class DirectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();
        $crawler = $client->request('GET', "https://sunrail.com/");
        $schedules = $crawler->filter('#menu-item-78 .sub-menu a');

        $schedules->each(
            function (Crawler $schedule) {
                Direction::query()->firstOrCreate(['name' => $schedule->text()]);
            }
        );
    }
}
