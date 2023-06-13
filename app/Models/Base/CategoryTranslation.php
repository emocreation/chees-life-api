<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoryTranslation
 *
 * @property int $id
 * @property int $category_id
 * @property string $locale
 * @property string $name
 * @property string|null $title
 * @property string|null $description
 *
 * @property Category $category
 *
 * @package App\Models\Base
 */
class CategoryTranslation extends Model
{
    public $timestamps = false;
    protected $table = 'category_translations';
    protected $casts = [
        'category_id' => 'int'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
