<?php

namespace App\Models;

use App\Models\Base\SocialMedia as BaseSocialMedia;
use App\Traits\Searchable;

class SocialMedia extends BaseSocialMedia
{
    use Searchable;

    protected $fillable = [
        'type',
        'link',
        'enable'
    ];
    public array $searchable = [
        'type', 'link', 'enable'
    ];
    protected $appends = ['type_text'];

    public function getTypeTextAttribute()
    {
        return \App\Enums\SocialMedia::getKey($this->type);
    }
}
