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
                    'category_slug' => $item->category->slug,
                    'category_name' => $item->category->name,
                    'slug' => $item->slug,
                    'price' => $item->price,
                    'hot' => $item->hot,
                    'title' => $item->title,
                    'subtitle' => $item->subtitle,
                    'image_url' => $item->image_url,
                    'preview_url' => $item->preview_url,
                    'optimized_url' => $item->optimized_url,
                    'service_description' => app()->getLocale() === 'tc' ? $item->description_tc : $item->description_en,
                    /*'service_description_en' => $item->description_en,
                    'service_description_tc' => $item->description_tc,*/
                    /*'service_descriptions' => $item->service_descriptions->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'description' => $item->description,
                        ];
                    })*/
                ];
            })
        ];
    }
}
