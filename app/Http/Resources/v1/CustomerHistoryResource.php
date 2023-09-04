<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerHistoryResource extends JsonResource
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
                'uuid' => $this->resource->uuid,
                'name' => $this->resource->name,
                'gender' => $this->resource->gender,
                'birthday' => $this->resource->birthday,
                'id_type' => $this->resource->id_type,
                'id_type_other' => $this->resource->id_type_other,
                'hkid' => $this->resource->hkid,
                'tel' => $this->resource->tel,
                'email' => $this->resource->email,
                'contact_address' => $this->resource->contact_address,
                'medical_record' => $this->resource->medical_record,
                'covid_diagnosed' => $this->resource->covid_diagnosed,
                'covid_close_contacts' => $this->resource->covid_close_contacts,
                'covid_date' => $this->resource->covid_date,
                'height' => $this->resource->height,
                'weight' => $this->resource->weight,
                'blood_date' => $this->resource->blood_date,
                'blood_time' => $this->resource->blood_time,
                'district' => $this->resource->district,
                'address' => $this->resource->address,
                'report' => $this->resource->report,
                'report_explanation' => $this->resource->report_explanation,
                'remark' => $this->resource->remark,
                'paid' => $this->resource->paid,
                'report_url' => $this->resource->report_url,
                'customer_history_details' => $this->resource->customer_history_details->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'price' => $item->price,
                    ];
                }),
            ]
        ];
    }
}
