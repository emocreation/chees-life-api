<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceTranslation
 * 
 * @property int $id
 * @property int $service_id
 * @property string $locale
 * @property string $title
 * @property string|null $subtitle
 * 
 * @property Service $service
 *
 * @package App\Models\Base
 */
class ServiceTranslation extends Model
{
	protected $table = 'service_translations';
	public $timestamps = false;

	protected $casts = [
		'service_id' => 'int'
	];

	public function service()
	{
		return $this->belongsTo(Service::class);
	}
}
