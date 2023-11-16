<?php

namespace App\Models;

use App\Models\Base\Coupon as BaseCoupon;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;

class Coupon extends BaseCoupon implements TranslatableContract
{
    use Translatable, Searchable;

    public array $translatedAttributes = ['name'];
    protected $fillable = [
        'type',
        'limitation',
        'value',
        'code',
        'valid_from',
        'valid_to',
        'quota',
        'used'
    ];

    protected $casts = [
        'limitation' => 'float',
        'value' => 'float',
        'valid_from' => 'datetime:Y-m-d',
        'valid_to' => 'datetime:Y-m-d',
        'quota' => 'int',
        'used' => 'int'
    ];
    public array $searchable = ['translations.name', 'code', 'valid_from', 'valid_to', 'quota', 'used', 'type', 'limitation', 'value'];


    /**
     * Scope a query to only include active users.
     *
     * @param Builder $query
     * @param string|null $date
     * @return Builder
     */
    public function scopeValidDate(Builder $query, ?string $date): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('valid_from')->whereNull('valid_to');
        })->orWhere(function ($q) use ($date) {
            $q->where('valid_from', '<=', $date)->where('valid_to', '>=', $date);
        })->orWhere(function ($q) use ($date) {
            $q->where('valid_from', '<=', $date)->whereNull('valid_to');
        })->orWhere(function ($q) use ($date) {
            $q->whereNull('valid_from')->where('valid_to', '>=', $date);
        });
    }

    public function scopeValidLimitation(Builder $query, ?float $amount): Builder
    {
        return $query->where(function ($q) use ($amount) {
            $q->whereNull('limitation')->orWhere('limitation', '<=', $amount);
        });
    }
}
