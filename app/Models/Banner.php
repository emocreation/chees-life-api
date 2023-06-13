<?php

namespace App\Models;

use App\Models\Base\Banner as BaseBanner;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Banner extends BaseBanner implements HasMedia
{
    use InteractsWithMedia, Searchable;

    public array $searchable = [
        'id', 'sequence', 'enable'
    ];
    protected $fillable = [
        'sequence',
        'enable'
    ];
    protected $appends = ['image_url', 'preview_url', 'optimized_url'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->sequence = Banner::max('sequence') + 1;
        });
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
        $query->orderByDesc('sequence');
    }
}
