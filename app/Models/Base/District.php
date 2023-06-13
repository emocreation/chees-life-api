<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\DistrictTranslation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class District
 *
 * @property int $id
 * @property int $sequence
 * @property int $extra_charge
 * @property bool $enable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|DistrictTranslation[] $district_translations
 *
 * @package App\Models\Base
 */
class District extends Model
{
    protected $table = 'districts';

    protected $casts = [
        'sequence' => 'int',
        'extra_charge' => 'int',
        'enable' => 'bool'
    ];

    public function district_translations()
    {
        return $this->hasMany(DistrictTranslation::class);
    }
}
