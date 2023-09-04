<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\LatestNewsTranslation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LatestNews
 * 
 * @property int $id
 * @property int $sequence
 * @property string|null $slug
 * @property Carbon $news_date
 * @property bool $enable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|LatestNewsTranslation[] $latest_news_translations
 *
 * @package App\Models\Base
 */
class LatestNews extends Model
{
	protected $table = 'latest_news';

	protected $casts = [
		'sequence' => 'int',
		'news_date' => 'datetime',
		'enable' => 'bool'
	];

	public function latest_news_translations()
	{
		return $this->hasMany(LatestNewsTranslation::class);
	}
}
