<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class FeedController extends Controller
{
    /**
     * @return array
     */
    public function index(): array
   {
       // 13 - Meadow Woods
       // 15 - Kissimmee
       // 10 - Church

       $sql = "
        SELECT feed_schedules.updated_at,
               stations.name                                                                                    station,
               directions.name                                                                                  direction,
               trains.name                                                                                      train,
               DATE_PART('minute', COALESCE(feed_schedules.arrival_time, schedules.time) - schedules.time)::int delay_time,
               COALESCE(feed_schedules.arrival_time, schedules.time)                                            arrival_time
        FROM schedules
               LEFT JOIN feed_schedules
                         ON feed_schedules.station_id = schedules.station_id
                           AND feed_schedules.train_id = schedules.train_id
               LEFT JOIN trains
                         ON schedules.train_id = trains.id
               LEFT JOIN directions
                         ON schedules.direction_id = directions.id
               LEFT JOIN stations
                         ON schedules.station_id = stations.id";

       return \DB::select($sql);
   }
}
