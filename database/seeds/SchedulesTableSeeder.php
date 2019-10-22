<?php

use App\Direction;
use App\Schedule;
use App\Station;
use App\Train;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Symfony\Component\DomCrawler\Crawler;

class SchedulesTableSeeder extends Seeder
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

        // Just truncate the table and start fresh.
        Schedule::query()->truncate();

        // Loop schedules.
        $schedules->each(function (Crawler $schedule) use ($client) {
            $link = $schedule->link();
            $direction = $schedule->text();
            $tables = $client->click($link)->filter('.schedule_table');

            // Loop tables.
            $tables->each(function (Crawler $table) use ($direction) {
                $trains = new Collection();

                // Loop rows.
                $table->filter('tr')->each(
                    function (Crawler $row) use ($trains, $direction) {
                        if ($row->filter('th')->text() === 'Train Number') {

                            // Loop train cells.
                            $row->filter('td')->each(
                                function (Crawler $cell) use ($trains, $direction) {
                                    $trains->push('P'.$cell->text());
                                }
                            );
                        } else {
                            $station = $row->filter('th')->text();

                            // Loop time cells.
                            $row->filter('td')->each(
                                function(Crawler $cell, $index) use ($station, $trains, $direction) {
                                    if ($cell->text()) {
                                        $rawTime = preg_replace('/[\*]+/', '', $cell->text());
                                        $train = Train::query()->where('name', $trains->get($index))->first();
                                        $direction = Direction::query()
                                            ->where('name', 'LIKE', "{$direction}%")->first();
                                        $station = Station::query()->where('name', $station)->first();

                                        // Create schedule item.
                                        Schedule::query()->create([
                                            'train_id' => $train->getAttribute('id'),
                                            'direction_id' => $direction->getAttribute('id'),
                                            'station_id' => $station->getAttribute('id'),
                                            'time' => Carbon::parse(strtotime($rawTime))->format('H:i:s'),
                                        ]);
                                    }
                                }
                            );
                        }
                    }
                );
            });
        });
    }
}
