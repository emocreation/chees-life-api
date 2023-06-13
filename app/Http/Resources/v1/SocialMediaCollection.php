<?php

namespace App\Http\Resources\v1;

use App\Enums\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SocialMediaCollection extends ResourceCollection
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
                    'type' => $item->type,
                    'type_text' => SocialMedia::getKey($item->type),
                    'link' => $item->link,
                ];
            })
        ];
    }
}
