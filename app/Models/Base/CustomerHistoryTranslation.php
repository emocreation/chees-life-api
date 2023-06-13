<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\CustomerHistory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerHistoryTranslation
 *
 * @property int $id
 * @property int $customer_history_id
 * @property string $locale
 * @property string $district
 *
 * @property CustomerHistory $customer_history
 *
 * @package App\Models\Base
 */
class CustomerHistoryTranslation extends Model
{
    public $timestamps = false;
    protected $table = 'customer_history_translations';
    protected $casts = [
        'customer_history_id' => 'int'
    ];

    public function customer_history()
    {
        return $this->belongsTo(CustomerHistory::class);
    }
}
