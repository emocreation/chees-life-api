<?php

namespace App\Models;

use App\Models\Base\ServiceDescription as BaseServiceDescription;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class ServiceDescription extends BaseServiceDescription implements TranslatableContract
{
    use Translatable;

    public array $translatedAttributes = ['description'];
    public array $searchable = ['description'];
    protected $fillable = ['service_id', 'sequence'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->sequence = ServiceDescription::where('service_id', $model->service_id)->max('sequence') + 1;
        });
    }
}
