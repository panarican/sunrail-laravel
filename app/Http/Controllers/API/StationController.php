<?php

namespace App\Http\Controllers\API;

use App\Station;

class StationController extends ModelController
{
    /**
     * @var Station $model
     */
    protected $model;

    /**
     * StationController constructor.
     * @param Station $model
     */
    public function __construct(Station $model)
    {
        parent::__construct($model);
    }
}
