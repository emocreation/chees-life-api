<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LatestNewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'id' => $this->resource->id,
                'news_date' => $this->resource->news_date,
                'slug' => $this->resource->slug,
                'title' => $this->resource->title,
                'introduction' => $this->resource->introduction,
                'description' => $this->resource->description,
                'image_url' => $this->resource->image_url,
                'preview_url' => $this->resource->preview_url,
                'optimized_url' => $this->resource->optimized_url,
            ]
        ];
    }
}
