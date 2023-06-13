<?php

namespace App\Http\Resources\v1;

use App\Enums\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HomeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'categories' => $this->collection['categories']->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'slug' => $item->slug,
                        'name' => $item->name,
                    ];
                }),
                'banners' => $this->collection['banners']->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'image_url' => $item->image_url,
                        'preview_url' => $item->preview_url,
                        'optimized_url' => $item->optimized_url
                    ];
                }),
                'services' => $this->collection['services']->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'slug' => $item->slug,
                        'price' => $item->price,
                        'hot' => $item->hot,
                        'title' => $item->title,
                        'subtitle' => $item->subtitle,
                        'descriptions' => $item->service_descriptions->map(function ($description) {
                            return [
                                'id' => $description->id,
                                'description' => $description->description
                            ];
                        }),
                        'image_url' => $item->image_url,
                        'preview_url' => $item->preview_url,
                        'optimized_url' => $item->optimized_url,
                    ];
                }),
                'reviews' => $this->collection['reviews']->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'review_date' => $item->review_date->format('Y-m-d'),
                        'rating' => $item->rating,
                        'customer_name' => $item->customer_name,
                        'content' => $item->content
                    ];
                }),
                'socialMedias' => $this->collection['socialMedias']->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'type' => $item->type,
                        'type_text' => SocialMedia::getKey($item->type),
                        'link' => $item->link,
                    ];
                })
            ]
        ];
    }
}
