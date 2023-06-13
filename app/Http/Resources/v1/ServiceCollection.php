<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceCollection extends ResourceCollection
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
                    'category_id' => $item->category_id,
                    'slug' => $item->slug,
                    'price' => $item->price,
                    'hot' => $item->hot,
                    'title' => $item->title,
                    'subtitle' => $item->subtitle,
                    'service_descriptions' => $item->service_descriptions->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'description' => $item->description,
                        ];
                    })
                ];
            })
        ];
    }
}
