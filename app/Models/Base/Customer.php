<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 * 
 * @property int $id
 * @property int|null $customer_id
 * @property string $name
 * @property string $gender
 * @property Carbon $birthday
 * @property string $hkid
 * @property string $tel
 * @property string $email
 * @property string $password
 * @property string|null $address
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property \App\Models\Customer|null $customer
 * @property Collection|\App\Models\Customer[] $customers
 *
 * @package App\Models\Base
 */
class Customer extends Model
{
	protected $table = 'customers';

	protected $casts = [
		'customer_id' => 'int',
		'birthday' => 'datetime',
		'email_verified_at' => 'datetime'
	];

	public function customer()
	{
		return $this->belongsTo(\App\Models\Customer::class);
	}

	public function customers()
	{
		return $this->hasMany(\App\Models\Customer::class);
	}
}
