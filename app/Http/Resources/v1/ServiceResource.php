<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
                'category_id' => $this->resource->category_id,
                'category_slug' => $this->resource->category->slug,
                'category_name' => $this->resource->category->name,
                'slug' => $this->resource->slug,
                'title' => $this->resource->title,
                'subtitle' => $this->resource->subtitle,
                'price' => $this->resource->price,
                'hot' => $this->resource->hot,
                'image_url' => $this->resource->image_url,
                'preview_url' => $this->resource->preview_url,
                'optimized_url' => $this->resource->optimized_url,
                'service_descriptions' => $this->resource->service_descriptions->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'description' => $item->description,
                    ];
                }),
                'service_details' => $this->resource->service_details->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'content' => $item->content
                    ];
                })
            ]
        ];
    }
}
