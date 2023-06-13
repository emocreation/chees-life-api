<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelHasPermission
 *
 * @property int $permission_id
 * @property string $model_type
 * @property int $model_id
 *
 * @property Permission $permission
 *
 * @package App\Models\Base
 */
class ModelHasPermission extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'model_has_permissions';
    protected $casts = [
        'permission_id' => 'int',
        'model_id' => 'int'
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
