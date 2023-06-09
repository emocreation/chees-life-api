<?php

namespace App\Models;

use App\Models\Base\TimeslotQuota as BaseTimeslotQuota;

class TimeslotQuota extends BaseTimeslotQuota
{
    protected $fillable = [
        'timeslot_id',
        'from',
        'to',
        'quota',
    ];
}
