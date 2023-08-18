<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return [
                    'id' => $item->id,
                    'image_web_url' => app()->getLocale() === 'tc' ? $item->images['image_web_tc_url'] : $item->images['image_web_en_url'],
                    'image_mobile_url' => app()->getLocale() === 'tc' ? $item->images['image_mobile_tc_url'] : $item->images['image_mobile_en_url'],
                ];
            })
        ];
    }
}
