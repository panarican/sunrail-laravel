<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    /**
     * @var array $hidden
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'id', 'name',
    ];
}
