<?php

namespace App\Models;

use App\Models\Base\Banner as BaseBanner;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
    protected $appends = ['images'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->sequence = Banner::max('sequence') + 1;
        });
    }

    /*public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 1000, 400)
            ->nonQueued();

        $this
            ->addMediaConversion('optimized')
            ->fit(Manipulations::FIT_CROP, 2000, 800)
            ->nonQueued();
    }*/

    public function getImagesAttribute()
    {
        $result = [
            'image_web_en_url' => $this->getFirstMediaUrl('web_banner_en'),
            'image_web_tc_url' => $this->getFirstMediaUrl('web_banner_tc'),
            'image_mobile_en_url' => $this->getFirstMediaUrl('mobile_banner_en'),
            'image_mobile_tc_url' => $this->getFirstMediaUrl('mobile_banner_tc'),
        ];
        $this->unsetRelation('media');
        return $result;
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
