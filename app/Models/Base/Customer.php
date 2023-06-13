<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\CustomerHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Customer
 *
 * @property int $id
 * @property string $name
 * @property string $gender
 * @property Carbon $birthday
 * @property string $hkid
 * @property string $tel
 * @property string $email
 * @property string $password
 * @property string|null $address
 * @property Carbon|null $email_verified_at
 * @property string|null $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|CustomerHistory[] $customer_histories
 *
 * @package App\Models\Base
 */
class Customer extends Authenticatable
{
    protected $table = 'customers';

    protected $casts = [
        'birthday' => 'datetime',
        'email_verified_at' => 'datetime'
    ];

    public function customer_histories()
    {
        return $this->hasMany(CustomerHistory::class);
    }
}
