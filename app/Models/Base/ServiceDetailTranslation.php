<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\ServiceDetail;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceDetailTranslation
 *
 * @property int $id
 * @property int $service_detail_id
 * @property string $locale
 * @property string|null $title
 * @property string|null $content
 *
 * @property ServiceDetail $service_detail
 *
 * @package App\Models\Base
 */
class ServiceDetailTranslation extends Model
{
    public $timestamps = false;
    protected $table = 'service_detail_translations';
    protected $casts = [
        'service_detail_id' => 'int'
    ];

    public function service_detail()
    {
        return $this->belongsTo(ServiceDetail::class);
    }
}
