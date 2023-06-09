<?php

namespace App\Models;

use App\Models\Base\Timeslot as BaseTimeslot;
use App\Traits\Searchable;

class Timeslot extends BaseTimeslot
{
    use Searchable;

    protected $fillable = [
        'available_date',
        'enable'
    ];
}
