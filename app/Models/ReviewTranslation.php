<?php

namespace App\Models;

use App\Models\Base\ReviewTranslation as BaseReviewTranslation;

class ReviewTranslation extends BaseReviewTranslation
{
	protected $fillable = [
		'review_id',
		'locale',
		'customer_name',
		'content'
	];
}
