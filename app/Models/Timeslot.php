<?php

namespace App\Models;

use App\Models\Base\Timeslot as BaseTimeslot;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;

class Timeslot extends BaseTimeslot
{
    use Searchable;

    protected $fillable = [
        'available_date',
        'enable'
    ];

    public function scopeEnabled(Builder $query): void
    {
        $query->where('enable', 1);
    }

    public function scopeSortDate(Builder $query): void
    {
        $query->orderBy('available_date');
    }

    public function scopeAvailableQuotas(Builder $query): void
    {
        $query->whereHas('timeslot_quotas', function ($query) {
            $query->where('quota', '>', 0);
        });
    }

    public function scopeAvailableDates(Builder $query): void
    {
        $query->where('available_date', '>', now());
    }
}
