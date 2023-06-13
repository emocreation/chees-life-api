<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Service;
use App\Models\ServiceDescriptionTranslation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceDescription
 *
 * @property int $id
 * @property int $service_id
 * @property int $sequence
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Service $service
 * @property Collection|ServiceDescriptionTranslation[] $service_description_translations
 *
 * @package App\Models\Base
 */
class ServiceDescription extends Model
{
    protected $table = 'service_descriptions';

    protected $casts = [
        'service_id' => 'int',
        'sequence' => 'int'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function service_description_translations()
    {
        return $this->hasMany(ServiceDescriptionTranslation::class);
    }
}
