<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Banner
 * 
 * @property int $id
 * @property int $sequence
 * @property bool $enable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class Banner extends Model
{
	protected $table = 'banners';

	protected $casts = [
		'sequence' => 'int',
		'enable' => 'bool'
	];
}
