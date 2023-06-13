<?php

namespace App\Models;

use App\Models\Base\SocialMedia as BaseSocialMedia;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;

class SocialMedia extends BaseSocialMedia
{
    use Searchable;

    public array $searchable = [
        'type', 'link', 'enable'
    ];
    protected $fillable = [
        'type',
        'link',
        'enable'
    ];
    protected $appends = ['type_text'];

    public function getTypeTextAttribute()
    {
        return \App\Enums\SocialMedia::getKey($this->type);
    }

    public function scopeEnabled(Builder $query): void
    {
        $query->where('enable', 1);
    }
}
