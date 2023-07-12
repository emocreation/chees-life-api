<?php

namespace App\Models;

use App\Models\Base\CustomerHistory as BaseCustomerHistory;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CustomerHistory extends BaseCustomerHistory implements TranslatableContract, HasMedia
{
    use Translatable, Searchable, InteractsWithMedia;

    public array $translatedAttributes = ['district'];
    public $appends = ['amount', 'report_url'];
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
        'stripe_id',
        'paid'
    ];

    public function getAmountAttribute()
    {
        return (int)$this->customer_history_details()->sum('price');
    }

    public function getReportUrlAttribute()
    {
        return $this->getFirstMediaUrl() !== '' ? $this->getFirstMediaUrl() : null;
    }
}
