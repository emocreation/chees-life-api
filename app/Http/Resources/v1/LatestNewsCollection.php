<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LatestNewsCollection extends ResourceCollection
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
                    'slug' => $item->slug,
                    'news_date' => $item->news_date,
                    'title' => $item->title,
                    'introduction' => $item->introduction,
                    'image_url' => $item->image_url,
                    'preview_url' => $item->preview_url,
                    'optimized_url' => $item->optimized_url,
                ];
            })
        ];
    }
}
