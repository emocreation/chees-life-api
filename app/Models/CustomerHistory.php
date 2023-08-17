<?php

namespace App\Models;

use App\Models\Base\CustomerHistory as BaseCustomerHistory;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CustomerHistory extends BaseCustomerHistory implements TranslatableContract, HasMedia
{
    use Translatable, Searchable, InteractsWithMedia;

    public array $translatedAttributes = ['district'];
    public $appends = ['amount', 'report_url'];
    protected $fillable = [
        'uuid',
        'order_no',
        'customer_id',
        'name',
        'gender',
        'birthday',
        'id_type',
        'hkid',
        'tel',
        'email',
        'contact_address',
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
        'paid',
        'locale'
    ];

    protected $casts = [
        'customer_id' => 'int',
        'birthday' => 'datetime:Y-m-d',
        'covid_diagnosed' => 'bool',
        'covid_close_contacts' => 'bool',
        'covid_date' => 'datetime:Y-m-d',
        'height' => 'float',
        'weight' => 'float',
        'blood_date' => 'datetime:Y-m-d',
        'paid' => 'bool'
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function getAmountAttribute()
    {
        return (int)$this->customer_history_details()->sum('price');
    }

    public function getReportUrlAttribute()
    {
        return $this->getFirstMediaUrl() !== '' ? $this->getFirstMediaUrl() : null;
    }

    public function scopeUuid(Builder $query, string $uuid)
    {
        $query->where('uuid', $uuid);
    }
}
