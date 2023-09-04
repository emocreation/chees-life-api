<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportExplanation
 * 
 * @property int $id
 * @property string $type
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class ReportExplanation extends Model
{
	protected $table = 'report_explanations';

	protected $casts = [
		'price' => 'float'
	];
}
