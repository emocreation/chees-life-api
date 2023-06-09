<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Medium
 * 
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property string $manipulations
 * @property string $custom_properties
 * @property string $generated_conversions
 * @property string $responsive_images
 * @property int|null $order_column
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class Medium extends Model
{
	protected $table = 'media';

	protected $casts = [
		'model_id' => 'int',
		'size' => 'int',
		'order_column' => 'int'
	];
}
