<?php

namespace App\Models;

use App\Models\Base\CustomerHistory as BaseCustomerHistory;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class CustomerHistory extends BaseCustomerHistory implements TranslatableContract
{
    use Translatable, Searchable;

    public array $translatedAttributes = ['district'];
    protected $fillable = [
        'customer_id',
        'name',
        'gender',
        'birthday',
        'hkid',
        'tel',
        'email',
        'medical_record',
        'covid_diagnosed',
        'covid_close_contacts',
        'covid_date',
        'height',
        'weight',
        'blood_date',
        'blood_time',
        'address',
        'report',
        'remark',
        'paid'
    ];

    public $appends = ['amount'];

    public function getAmountAttribute()
    {
        return (int)$this->customer_history_details()->sum('price');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
