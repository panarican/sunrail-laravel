<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    const TABLE_NAME = 'schedules';

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
            $table->unsignedInteger('direction_id');
            $table->unsignedInteger('station_id');
            $table->foreign('train_id')->references('id')->on(CreateTrainsTable::TABLE_NAME);
            $table->foreign('direction_id')->references('id')->on(CreateDirectionsTable::TABLE_NAME);
            $table->foreign('station_id')->references('id')->on(CreateStationsTable::TABLE_NAME);
            $table->time('time');
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
