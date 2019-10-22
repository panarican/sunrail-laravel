<?php

namespace App\Http\Controllers\API;

use App\Schedule;

class ScheduleController extends ModelController
{
    /**
     * @var Schedule $model
     */
    protected $model;

    /**
     * ScheduleController constructor.
     * @param Schedule $model
     */
    public function __construct(Schedule $model)
    {
        parent::__construct($model);
    }
}
