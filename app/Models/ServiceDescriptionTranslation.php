<?php

namespace App\Models;

use App\Models\Base\ServiceDescriptionTranslation as BaseServiceDescriptionTranslation;

class ServiceDescriptionTranslation extends BaseServiceDescriptionTranslation
{
	protected $fillable = [
		'service_description_id',
		'locale',
		'description'
	];
}
