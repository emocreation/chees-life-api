<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Timeslot;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TimeslotQuota
 *
 * @property int $id
 * @property int $timeslot_id
 * @property Carbon $from
 * @property Carbon $to
 * @property int $quota
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Timeslot $timeslot
 *
 * @package App\Models\Base
 */
class TimeslotQuota extends Model
{
    protected $table = 'timeslot_quotas';

    protected $casts = [
        'timeslot_id' => 'int',
        'from' => 'datetime',
        'to' => 'datetime',
        'quota' => 'int'
    ];

    public function timeslot()
    {
        return $this->belongsTo(Timeslot::class);
    }
}
