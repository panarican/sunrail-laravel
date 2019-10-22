<?php

use App\Train;
use Goutte\Client;
use Illuminate\Database\Seeder;
use Symfony\Component\DomCrawler\Crawler;

class TrainsTableSeeder extends Seeder
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

        // Loop schedules.
        $schedules->each(function (Crawler $schedule) use ($client) {
            $link = $schedule->link();
            $trains = $client->click($link)->filter('.scheduleTable-trainIdRow td');

            // Loop trains.
            $trains->each(
                function (Crawler $cell) {
                    $name = 'P'.$cell->text();
                    Train::query()->firstOrCreate(['name' => $name]);
                }
            );
        });
    }
}
