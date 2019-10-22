<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Station extends Model
{
    const COLUMNS = [
        'id', 'name', 'address', 'city', 'state', 'postal_code', 'telephone', 'latitude', 'longitude',
    ];

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $with = [
        'schedules', 'feedSchedules',
    ];

    /**
     * @var array $fillable
     */
    protected $fillable = self::COLUMNS;

    /**
     * @return Collection
     */
    public function getSchedules(): Collection
    {
        return $this->getAttribute('schedules');
    }

    /**
     * @return Collection
     */
    public function getFeedSchedules(): Collection
    {
        return $this->getAttribute('feedSchedules');
    }

    /**
     * @return HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * @return HasMany
     */
    public function feedSchedules(): HasMany
    {
        return $this->hasMany(FeedSchedule::class);
    }
}
