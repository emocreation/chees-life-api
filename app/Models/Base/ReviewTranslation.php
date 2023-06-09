<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Review;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReviewTranslation
 * 
 * @property int $id
 * @property int $review_id
 * @property string $locale
 * @property string $customer_name
 * @property string $content
 * 
 * @property Review $review
 *
 * @package App\Models\Base
 */
class ReviewTranslation extends Model
{
	protected $table = 'review_translations';
	public $timestamps = false;

	protected $casts = [
		'review_id' => 'int'
	];

	public function review()
	{
		return $this->belongsTo(Review::class);
	}
}
