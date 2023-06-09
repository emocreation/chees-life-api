<?php

namespace App\Models;

use App\Models\Base\CustomerHistoryDetailTranslation as BaseCustomerHistoryDetailTranslation;

class CustomerHistoryDetailTranslation extends BaseCustomerHistoryDetailTranslation
{
	protected $fillable = [
		'customer_history_detail_id',
		'locale',
		'title'
	];
}
