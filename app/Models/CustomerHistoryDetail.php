<?php

namespace App\Models;

use App\Models\Base\CustomerHistoryDetail as BaseCustomerHistoryDetail;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class CustomerHistoryDetail extends BaseCustomerHistoryDetail implements TranslatableContract
{
    use Translatable;

    public array $translatedAttributes = ['title'];
    protected $fillable = [
        'customer_history_id', 'price'
    ];

}
