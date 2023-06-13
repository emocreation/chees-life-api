<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\CustomerHistory;
use App\Models\CustomerHistoryDetailTranslation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerHistoryDetail
 *
 * @property int $id
 * @property int $customer_history_id
 * @property int $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property CustomerHistory $customer_history
 * @property Collection|CustomerHistoryDetailTranslation[] $customer_history_detail_translations
 *
 * @package App\Models\Base
 */
class CustomerHistoryDetail extends Model
{
    protected $table = 'customer_history_details';

    protected $casts = [
        'customer_history_id' => 'int',
        'price' => 'int'
    ];

    public function customer_history()
    {
        return $this->belongsTo(CustomerHistory::class);
    }

    public function customer_history_detail_translations()
    {
        return $this->hasMany(CustomerHistoryDetailTranslation::class);
    }
}
