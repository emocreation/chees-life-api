<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Coupon
 * 
 * @property int $id
 * @property string $type
 * @property float|null $limitation
 * @property float $value
 * @property string $code
 * @property Carbon|null $valid_from
 * @property Carbon|null $valid_to
 * @property int $quota
 * @property int $used
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class Coupon extends Model
{
	protected $table = 'coupons';

	protected $casts = [
		'limitation' => 'float',
		'value' => 'float',
		'valid_from' => 'datetime',
		'valid_to' => 'datetime',
		'quota' => 'int',
		'used' => 'int'
	];
}
