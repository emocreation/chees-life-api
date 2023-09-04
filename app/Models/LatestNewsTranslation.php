<?php

namespace App\Models;

use App\Models\Base\LatestNewsTranslation as BaseLatestNewsTranslation;

class LatestNewsTranslation extends BaseLatestNewsTranslation
{
	protected $fillable = [
		'latest_news_id',
		'locale',
		'title',
		'introduction',
		'description'
	];
}
