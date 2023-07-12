<?php

namespace App\Models;

use App\Models\Base\Service as BaseService;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Service extends BaseService implements TranslatableContract, HasMedia
{
    use Translatable, Sluggable, Searchable, InteractsWithMedia;

    public array $translatedAttributes = ['title', 'subtitle'];
    public array $searchable = [
        'slug', 'enable', 'translations.title', 'translations.subtitle'
    ];
    protected $fillable = [
        'sequence',
        'category_id',
        'slug',
        'price',
        'hot',
        'enable'
    ];
    protected $appends = ['image_url', 'preview_url', 'optimized_url', 'category_name'];

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
        return $this->getFirstMediaUrl() !== '' ? $this->getFirstMediaUrl() : null;
    }

    public function getPreviewUrlAttribute()
    {
        return $this->getFirstMedia() !== null ? $this->getFirstMedia()->getUrl('preview') : null;
    }

    public function getOptimizedUrlAttribute()
    {
        return $this->getFirstMedia() !== null ? $this->getFirstMedia()->getUrl('optimized') : null;
    }

    public function getCategoryNameAttribute()
    {
        return $this->category->name;
    }

    public function scopeEnabled(Builder $query): void
    {
        $query->where('enable', 1);
    }

    public function scopeSequence(Builder $query): void
    {
        $query->orderByDesc('sequence');
    }

    public function scopeCategorySlugOrId(Builder $query, string $slug)
    {
        $query->whereHas('category', function (Builder $query) use ($slug) {
            $query->where('slug', $slug)->orWhere('id', $slug);
        });
    }

    public function scopeSlugOrId(Builder $query, string $slug)
    {
        $query->where('slug', $slug)->orWhere('id', $slug);
    }

    public function service_details()
    {
        return $this->hasMany(ServiceDetail::class)->orderBy('sequence');
    }

    public function service_descriptions()
    {
        return $this->hasMany(ServiceDescription::class)->orderBy('sequence');
    }

}
