<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    const COLUMNS = [
        'id', 'name', 'created_at', 'updated_at',
    ];

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = self::COLUMNS;
}
