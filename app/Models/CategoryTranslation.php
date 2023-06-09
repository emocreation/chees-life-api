<?php

namespace App\Models;

use App\Models\Base\CategoryTranslation as BaseCategoryTranslation;

class CategoryTranslation extends BaseCategoryTranslation
{
	protected $fillable = [
		'category_id',
		'locale',
		'name',
		'title',
		'description'
	];
}
