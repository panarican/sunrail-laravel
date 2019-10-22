<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedSchedulesTable extends Migration
{
    const DIRECTIONS = CreateDirectionsTable::TABLE_NAME;
    const STATIONS = CreateStationsTable::TABLE_NAME;
    const TABLE_NAME = 'feed_schedules';
    const TRAINS = CreateTrainsTable::TABLE_NAME;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('train_id');
            $table->unsignedInteger('trip_id');
            $table->unsignedInteger('direction_id');
            $table->unsignedInteger('station_id');
            $table->foreign('train_id')->references('id')->on(self::TRAINS);
            $table->foreign('trip_id')->references('id')->on(self::TRAINS);
            $table->foreign('direction_id')->references('id')->on(self::DIRECTIONS);
            $table->foreign('station_id')->references('id')->on(self::STATIONS);
            $table->date('arrival_date');
            $table->time('arrival_time');
            $table->boolean('delay_flag');
            $table->boolean('realtime_flag');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
