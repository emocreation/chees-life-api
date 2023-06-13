<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TimeslotCollection extends ResourceCollection
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
                    'available_date' => $item->available_date->format('Y-m-d'),
                    'quotas' => $item->timeslot_quotas->map(function ($row) {
                        return [
                            'id' => $row->id,
                            'from' => $row->from->format('H:i'),
                            'to' => $row->to->format('H:i'),
                            'quota' => $row->quota
                        ];
                    })
                ];
            })
        ];
    }
}
