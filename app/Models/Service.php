<?php

namespace App\Models;

use App\Models\Base\Service as BaseService;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Service extends BaseService implements TranslatableContract, HasMedia
{
    use Translatable, Sluggable, Searchable, InteractsWithMedia;

    public array $translatedAttributes = ['title', 'subtitle'];
    protected $fillable = [
        'sequence',
        'slug',
        'price',
        'hot',
        'enable'
    ];

    public array $searchable = [
        'slug', 'enable', 'translations.title', 'translations.subtitle'
    ];

    protected $appends = ['image_url', 'preview_url', 'optimized_url'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->sequence = Service::max('sequence') + 1;
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
                'source' => 'title',
                'onUpdate' => true,
            ]
        ];
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 1000, 400)
            ->nonQueued();

        $this
            ->addMediaConversion('optimized')
            ->fit(Manipulations::FIT_CROP, 2000, 800)
            ->nonQueued();
    }

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl();
    }

    public function getPreviewUrlAttribute()
    {
        return $this->getFirstMedia() !== null ? $this->getFirstMedia()->getUrl('preview') : null;
    }

    public function getOptimizedUrlAttribute()
    {
        return $this->getFirstMedia() !== null ? $this->getFirstMedia()->getUrl('optimized') : null;
    }
}
