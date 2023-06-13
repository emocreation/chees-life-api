<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\ServiceDescription;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceDescriptionTranslation
 *
 * @property int $id
 * @property int $service_description_id
 * @property string $locale
 * @property string $description
 *
 * @property ServiceDescription $service_description
 *
 * @package App\Models\Base
 */
class ServiceDescriptionTranslation extends Model
{
    public $timestamps = false;
    protected $table = 'service_description_translations';
    protected $casts = [
        'service_description_id' => 'int'
    ];

    public function service_description()
    {
        return $this->belongsTo(ServiceDescription::class);
    }
}
