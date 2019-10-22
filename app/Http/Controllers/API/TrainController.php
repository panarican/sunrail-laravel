<?php

namespace App\Http\Controllers\API;

use App\Train;

class TrainController extends ModelController
{
    /**
     * @var Train $model
     */
    protected $model;

    /**
     * TrainController constructor.
     * @param Train $model
     */
    public function __construct(Train $model)
    {
        parent::__construct($model);
    }
}
