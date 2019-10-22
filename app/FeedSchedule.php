<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedSchedule extends Model
{
    /**
     * @var array $fillable
     */
    protected $fillable = [
        'id',
        'station_id',
        'direction_id',
        'arrival_date',
        'arrival_time',
        'delay_flag',
        'realtime_flag',
        'train_id',
        'trip_id',
    ];

    /**
     * @var array $appends
     */
    protected $appends = [
        'direction', 'station', 'train',
    ];

    /**
     * @return string
     */
    public function getDirectionAttribute(): string
    {
        return $this->direction()->pluck('name')->first();
    }

    /**
     * @return string
     */
    public function getStationAttribute(): string
    {
        return $this->station()->pluck('name')->first();
    }

    /**
     * @return string
     */
    public function getTrainAttribute(): string
    {
        return $this->train()->pluck('name')->first();
    }

    /**
     * @return BelongsTo
     */
    public function direction(): BelongsTo
    {
        return $this->belongsTo(Direction::class);
    }

    /**
     * @return BelongsTo
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * @return BelongsTo
     */
    public function train(): BelongsTo
    {
        return $this->belongsTo(Train::class);
    }
}
