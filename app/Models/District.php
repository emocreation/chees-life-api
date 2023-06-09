<?php

namespace App\Models;

use App\Models\Base\District as BaseDistrict;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class District extends BaseDistrict implements TranslatableContract
{
    use Translatable, Searchable;

    public array $translatedAttributes = ['name'];
    protected $fillable = [
        'sequence',
        'extra_charge',
        'enable'
    ];

    public array $searchable = [
        'extra_charge', 'enable', 'translations.name'
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->sequence = District::max('sequence') + 1;
        });
    }
}
