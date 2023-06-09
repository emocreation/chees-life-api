<?php

namespace App\Models;

use App\Models\Base\Category as BaseCategory;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class Category extends BaseCategory implements TranslatableContract
{
    use Translatable, Sluggable, Searchable;

    public array $translatedAttributes = ['name', 'title', 'description'];
    protected $fillable = [
        'sequence',
        'slug',
        'enable'
    ];
    public array $searchable = [
        'slug', 'enable', 'translations.name', 'translations.title', 'translations.description'
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->sequence = Category::max('sequence') + 1;
            //$model->slug = Str::slug($model->name);
        });

        //self::saving(fn($model) => $model->slug = Str::slug($model->name));
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
                'source' => 'name'
            ]
        ];
    }
}
