<?php

namespace App\Models;

use App\Models\Base\Category as BaseCategory;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;

class Category extends BaseCategory implements TranslatableContract
{
    use Translatable, Sluggable, Searchable;

    public array $translatedAttributes = ['name', 'title', 'description'];
    public array $searchable = [
        'slug', 'enable', 'translations.name', 'translations.title', 'translations.description'
    ];
    protected $fillable = [
        'sequence',
        'slug',
        'enable'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->sequence = 1;
        });
        self::created(function ($model) {
            Category::where('id', '!=', $model->id)->increment('sequence');
        });
        self::deleted(function ($model) {
            Category::where('sequence', '>', $model->sequence)->decrement('sequence');
        });
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
            ]
        ];
    }

    public function scopeEnabled(Builder $query): void
    {
        $query->where('enable', 1);
    }

    public function scopeSequence(Builder $query): void
    {
        $query->orderBy('sequence');
    }

    public function scopeSlugOrId(Builder $query, string $slug)
    {
        $query->where('slug', $slug)->orWhere('id', $slug);
    }
}
