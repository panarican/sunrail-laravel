<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    const COLUMNS = [
        'id', 'direction_id', 'station_id', 'train_id', 'time', 'created_at', 'updated_at',
    ];

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * @var array $appends
     */
    protected $appends = [
        'direction', 'station', 'train',
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = self::COLUMNS;

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
