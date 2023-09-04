<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\LatestNews;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LatestNewTranslation
 * 
 * @property int $id
 * @property int $latest_new_id
 * @property string $locale
 * @property string $title
 * @property string|null $introduction
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property LatestNews $latest_news
 *
 * @package App\Models\Base
 */
class LatestNewTranslation extends Model
{
	protected $table = 'latest_new_translations';

	protected $casts = [
		'latest_new_id' => 'int'
	];

	public function latest_news()
	{
		return $this->belongsTo(LatestNews::class, 'latest_new_id');
	}
}
