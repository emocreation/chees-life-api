<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\TimeslotQuota;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Timeslot
 * 
 * @property int $id
 * @property Carbon $available_date
 * @property bool $enable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|TimeslotQuota[] $timeslot_quotas
 *
 * @package App\Models\Base
 */
class Timeslot extends Model
{
	protected $table = 'timeslots';

	protected $casts = [
		'available_date' => 'datetime',
		'enable' => 'bool'
	];

	public function timeslot_quotas()
	{
		return $this->hasMany(TimeslotQuota::class);
	}
}
