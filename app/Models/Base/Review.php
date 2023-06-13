<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\ReviewTranslation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Review
 *
 * @property int $id
 * @property Carbon $review_date
 * @property int $rating
 * @property bool $enable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|ReviewTranslation[] $review_translations
 *
 * @package App\Models\Base
 */
class Review extends Model
{
    protected $table = 'reviews';

    protected $casts = [
        'review_date' => 'datetime',
        'rating' => 'int',
        'enable' => 'bool'
    ];

    public function review_translations()
    {
        return $this->hasMany(ReviewTranslation::class);
    }
}
