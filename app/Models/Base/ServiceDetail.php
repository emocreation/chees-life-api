<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Service;
use App\Models\ServiceDetailTranslation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceDetail
 *
 * @property int $id
 * @property int $service_id
 * @property int $sequence
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Service $service
 * @property Collection|ServiceDetailTranslation[] $service_detail_translations
 *
 * @package App\Models\Base
 */
class ServiceDetail extends Model
{
    protected $table = 'service_details';

    protected $casts = [
        'service_id' => 'int',
        'sequence' => 'int'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function service_detail_translations()
    {
        return $this->hasMany(ServiceDetailTranslation::class);
    }
}
