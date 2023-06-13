<?php

namespace App\Models;

use App\Models\Base\ServiceDetailTranslation as BaseServiceDetailTranslation;

class ServiceDetailTranslation extends BaseServiceDetailTranslation
{
    protected $fillable = [
        'service_detail_id',
        'locale',
        'title',
        'content'
    ];
}
