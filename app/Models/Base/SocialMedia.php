<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SocialMedia
 *
 * @property int $id
 * @property int $type
 * @property string|null $link
 * @property bool $enable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class SocialMedia extends Model
{
    protected $table = 'social_medias';

    protected $casts = [
        'type' => 'int',
        'enable' => 'bool'
    ];
}
