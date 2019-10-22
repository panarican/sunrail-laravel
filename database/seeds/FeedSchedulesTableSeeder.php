<?php

use App\Direction;
use App\FeedSchedule;
use App\Station;
use App\Train;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Seeder;

class FeedSchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     * @throws GuzzleException
     */
    public function run()
    {
        // Define client.
        $client = new Client();

        // Make request.
        $feed = $client->request('POST', 'https://sunrail.com/wp-admin/admin-ajax.php', [
            'form_params' => [
                'action' => 'get_station_feed',
            ],
        ]);

        // Get contents.
        $contents = json_decode($feed->getBody()->getContents());

        // Just truncate the table and start fresh.
        FeedSchedule::query()->truncate();

        // Loop through stations.
        foreach ($contents as $stationFeed) {
            // Remove everything after '/'. Formatting of names are different from the original schedule name.
            $stationName = trim(strtolower(explode('/', $stationFeed->Name)[0]));

            // Find a match. (We are using postgres so just use ILIKE to ignore casing)
            $station = Station::query()
                ->where('name', 'ILIKE', $stationName . '%')->first();

            // Skip if station is missing.
            if (!$station) {
                continue;
            }

            // Update Latitude and Longitude values.
            $station->setAttribute('latitude', $stationFeed->Lat)->setAttribute('longitude', $stationFeed->Lon)->save();

            // Loop through directions.
            foreach ($stationFeed->Directions as $directionFeed) {
                $direction = Direction::query()
                    ->where('name', 'LIKE', $directionFeed->Direction . '%')->first();

                // Skip if direction is missing.
                if (!$direction) {
                    continue;
                }

                // Loop through stop times.
                foreach ($directionFeed->StopTimes as $stopTimeFeed) {
                    $train = Train::query()->where('name', $stopTimeFeed->TrainId)->first();
                    $trip = Train::query()->where('name', $stopTimeFeed->TripId)->first();

                    // Skip if train or trip is missing.
                    if (!$train || !$trip) {
                        continue;
                    }

                    // Create feed schedule item.
                    FeedSchedule::query()->create([
                        'station_id' => $station->getAttribute('id'),
                        'direction_id' => $direction->getAttribute('id'),
                        'arrival_date' => Carbon::parse($stopTimeFeed->ArrivalDate)->format('Y-m-d'),
                        'arrival_time' => $this->getArrivalTime($stopTimeFeed),
                        'delay_flag' => $stopTimeFeed->DelayFlag,
                        'realtime_flag' => $stopTimeFeed->RealtimeFlag,
                        'train_id' => $train->getAttribute('id'),
                        'trip_id' => $trip->getAttribute('id'),
                    ]);
                }
            }
        };
    }

    /**
     * @param stdClass $stopTimeFeed
     * @return string
     */
    private function getArrivalTime(stdClass $stopTimeFeed): string
    {
        // Break apart the time.
        $timeParts = explode(' ', $stopTimeFeed->ArrivalTime);
        $amPm = explode('*', $timeParts[1])[0];
        $timeHourMinutes = explode(':', $timeParts[0]);

        // Format the time pieces.
        $hour = (int) $timeHourMinutes[0];
        $hour = $hour === 0 ? 1 : $hour;
        $minutes = str_pad((int) $timeHourMinutes[1], 2, '0', STR_PAD_LEFT);

        // Glue the time back together.
        $fixedTime = strtotime("{$hour}:{$minutes} {$amPm}");

        return Carbon::parse($fixedTime)->format('H:i:s');
    }
}
