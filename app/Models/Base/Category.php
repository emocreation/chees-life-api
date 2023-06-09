<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\CategoryTranslation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * 
 * @property int $id
 * @property int $sequence
 * @property string $slug
 * @property bool $enable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|CategoryTranslation[] $category_translations
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
}
