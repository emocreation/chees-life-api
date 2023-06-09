<?php

namespace App\Models;

use App\Models\Base\Review as BaseReview;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Review extends BaseReview implements TranslatableContract
{
    use Translatable, Searchable;

    public array $translatedAttributes = ['customer_name', 'content'];
    protected $fillable = [
        'review_date',
        'rating',
        'enable'
    ];

    public array $searchable = [
        'review_date', 'rating', 'enable', 'translations.customer_name', 'translations.content'
    ];
}
