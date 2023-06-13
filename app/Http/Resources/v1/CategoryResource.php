<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
                'slug' => $this->resource->slug,
                'name' => $this->resource->name,
                'title' => $this->resource->title,
                'description' => $this->resource->description
            ]
        ];
    }
}
