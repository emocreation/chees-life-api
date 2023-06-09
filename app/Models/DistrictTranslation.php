<?php

namespace App\Models;

use App\Models\Base\DistrictTranslation as BaseDistrictTranslation;

class DistrictTranslation extends BaseDistrictTranslation
{
	protected $fillable = [
		'district_id',
		'locale',
		'name'
	];
}
