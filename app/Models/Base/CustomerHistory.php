<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Customer;
use App\Models\CustomerHistoryDetail;
use App\Models\CustomerHistoryTranslation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerHistory
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string $name
 * @property string $gender
 * @property Carbon $birthday
 * @property string $hkid
 * @property string $tel
 * @property string $email
 * @property string $medical_record
 * @property bool $covid_diagnosed
 * @property bool $covid_close_contacts
 * @property Carbon|null $covid_date
 * @property float $height
 * @property float $weight
 * @property Carbon $blood_date
 * @property string $blood_time
 * @property string $address
 * @property string $report
 * @property string|null $remark
 * @property string|null $stripe_id
 * @property bool $paid
 * @property string $locale
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Customer|null $customer
 * @property Collection|CustomerHistoryDetail[] $customer_history_details
 * @property Collection|CustomerHistoryTranslation[] $customer_history_translations
 *
 * @package App\Models\Base
 */
class CustomerHistory extends Model
{
    protected $table = 'customer_histories';

    protected $casts = [
        'customer_id' => 'int',
        'birthday' => 'datetime',
        'covid_diagnosed' => 'bool',
        'covid_close_contacts' => 'bool',
        'covid_date' => 'datetime',
        'height' => 'float',
        'weight' => 'float',
        'blood_date' => 'datetime',
        'paid' => 'bool'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customer_history_details()
    {
        return $this->hasMany(CustomerHistoryDetail::class);
    }

    public function customer_history_translations()
    {
        return $this->hasMany(CustomerHistoryTranslation::class);
    }
}
