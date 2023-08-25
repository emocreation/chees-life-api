<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\CategoryTranslation;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * 
 * @property int $id
 * @property int $sequence
 * @property string|null $slug
 * @property bool $enable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $youtube
 * 
 * @property Collection|CategoryTranslation[] $category_translations
 * @property Collection|Service[] $services
 *
 * @package App\Models\Base
 */
class Category extends Model
{
	protected $table = 'categories';

	protected $casts = [
		'sequence' => 'int',
		'enable' => 'bool'
	];

	public function category_translations()
	{
		return $this->hasMany(CategoryTranslation::class);
	}

	public function services()
	{
		return $this->hasMany(Service::class);
	}
}
