<?php

namespace App\Models;

use App\Models\Base\ServiceTranslation as BaseServiceTranslation;

class ServiceTranslation extends BaseServiceTranslation
{
    protected $fillable = [
        'service_id',
        'locale',
        'title',
        'subtitle'
    ];
}
