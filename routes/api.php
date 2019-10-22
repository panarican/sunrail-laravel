<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace('API')->group(function () {
    // api resources.
    Route::apiResources([
        'directions' => 'DirectionController',
        'trains' => 'TrainController',
        'stations' => 'StationController',
        'schedules' => 'ScheduleController',
        'feed' => 'FeedController',
    ]);
});
