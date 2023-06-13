<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
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
                    'review_date' => $item->review_date->format('Y-m-d'),
                    'rating' => $item->rating,
                    'customer_name' => $item->customer_name,
                    'content' => $item->content
                ];
            })
        ];
    }
}
