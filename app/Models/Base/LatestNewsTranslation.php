<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\LatestNews;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LatestNewsTranslation
 * 
 * @property int $id
 * @property int $latest_news_id
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
class LatestNewsTranslation extends Model
{
	protected $table = 'latest_news_translations';

	protected $casts = [
		'latest_news_id' => 'int'
	];

	public function latest_news()
	{
		return $this->belongsTo(LatestNews::class);
	}
}
