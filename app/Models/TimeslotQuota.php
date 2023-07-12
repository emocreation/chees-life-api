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

    protected $casts = [
        'timeslot_id' => 'int',
        'from' => 'datetime:H:i:s',
        'to' => 'datetime:H:i:s',
        'quota' => 'int'
    ];
}
