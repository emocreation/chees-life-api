<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\CustomerHistoryDetail;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerHistoryDetailTranslation
 * 
 * @property int $id
 * @property int $customer_history_detail_id
 * @property string $locale
 * @property string $title
 * 
 * @property CustomerHistoryDetail $customer_history_detail
 *
 * @package App\Models\Base
 */
class CustomerHistoryDetailTranslation extends Model
{
	protected $table = 'customer_history_detail_translations';
	public $timestamps = false;

	protected $casts = [
		'customer_history_detail_id' => 'int'
	];

	public function customer_history_detail()
	{
		return $this->belongsTo(CustomerHistoryDetail::class);
	}
}
