<?php

namespace App\Http\Controllers\API;

use App\Direction;

class DirectionController extends ModelController
{
    /**
     * @var Direction $model
     */
    protected $model;

    /**
     * DirectionController constructor.
     * @param Direction $model
     */
    public function __construct(Direction $model)
    {
        parent::__construct($model);
    }
}
