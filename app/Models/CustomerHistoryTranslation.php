<?php

namespace App\Models;

use App\Models\Base\CustomerHistoryTranslation as BaseCustomerHistoryTranslation;

class CustomerHistoryTranslation extends BaseCustomerHistoryTranslation
{
	protected $fillable = [
		'customer_history_id',
		'locale',
		'district'
	];
}
