<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\District;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DistrictTranslation
 * 
 * @property int $id
 * @property int $district_id
 * @property string $locale
 * @property string $name
 * 
 * @property District $district
 *
 * @package App\Models\Base
 */
class DistrictTranslation extends Model
{
	protected $table = 'district_translations';
	public $timestamps = false;

	protected $casts = [
		'district_id' => 'int'
	];

	public function district()
	{
		return $this->belongsTo(District::class);
	}
}
