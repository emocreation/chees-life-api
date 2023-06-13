<?php

namespace App\Models;

use App\Models\Base\Review as BaseReview;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;

class Review extends BaseReview implements TranslatableContract
{
    use Translatable, Searchable;

    public array $translatedAttributes = ['customer_name', 'content'];
    public array $searchable = [
        'review_date', 'rating', 'enable', 'translations.customer_name', 'translations.content'
    ];
    protected $fillable = [
        'review_date',
        'rating',
        'enable'
    ];

    public function scopeEnabled(Builder $query): void
    {
        $query->where('enable', 1);
    }

    public function scopeSortDate(Builder $query): void
    {
        $query->orderByDesc('created_at');
    }
}

