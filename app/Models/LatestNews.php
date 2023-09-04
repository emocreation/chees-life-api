<?php

namespace App\Models;

use App\Models\Base\LatestNews as BaseLatestNews;
use App\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LatestNews extends BaseLatestNews implements TranslatableContract, HasMedia
{
    use Translatable, Sluggable, Searchable, InteractsWithMedia;

    public array $translatedAttributes = ['title', 'introduction', 'description'];
    public array $searchable = [
        'slug', 'enable', 'translations.title', 'translations.introduction', 'translations.description'
    ];
    protected $fillable = [
        'slug',
        'news_date',
        'enable'
    ];
    protected $casts = [
        'news_date' => 'datetime:Y-m-d',
    ];
    protected $appends = ['image_url', 'preview_url', 'optimized_url'];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->sequence = 1;
        });
        self::created(function ($model) {
            LatestNews::where('id', '!=', $model->id)->increment('sequence');
        });
        self::deleted(function ($model) {
            LatestNews::where('sequence', '>', $model->sequence)->decrement('sequence');
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
        $query->where(function ($q) use ($slug) {
            return $q->where('slug', $slug)->orWhere('id', $slug);
        });
    }
}
