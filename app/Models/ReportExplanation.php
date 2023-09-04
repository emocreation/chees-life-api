<?php

namespace App\Models;

use App\Models\Base\ReportExplanation as BaseReportExplanation;

class ReportExplanation extends BaseReportExplanation
{
	protected $fillable = [
		'type',
		'price'
	];
}
