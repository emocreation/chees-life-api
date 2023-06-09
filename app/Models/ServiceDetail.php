<?php

namespace App\Models;

use App\Models\Base\ServiceDetail as BaseServiceDetail;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class ServiceDetail extends BaseServiceDetail implements TranslatableContract
{
    use Translatable;

    public array $translatedAttributes = ['title', 'content'];
    protected $fillable = ['service_id', 'sequence'];
    public array $searchable = ['title', 'content'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->sequence = ServiceDetail::where('service_id', $model->service_id)->max('sequence') + 1;
        });
    }
}
