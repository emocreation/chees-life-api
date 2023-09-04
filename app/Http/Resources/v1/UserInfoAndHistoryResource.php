<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoAndHistoryResource extends JsonResource
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
                'id' => $this->resource->id,
                'name' => $this->resource->name,
                'gender' => $this->resource->gender,
                'birthday' => $this->resource->birthday,
                'hkid' => $this->resource->hkid,
                'tel' => $this->resource->tel,
                'email' => $this->resource->email,
                'address' => $this->resource->address,
                'email_verified_at' => $this->resource->email_verified_at,
                'created_at' => $this->resource->created_at,
                'customer_histories' => $this->resource->customer_histories->map(function ($row) {
                    return [
                        'id' => $row->id,
                        'uuid' => $row->uuid,
                        'order_no' => $row->order_no,
                        'name' => $row->name,
                        'gender' => $row->gender,
                        'birthday' => $row->birthday,
                        'id_type' => $row->id_type,
                        'id_type_other' => $row->id_type_other,
                        'hkid' => $row->hkid,
                        'tel' => $row->tel,
                        'email' => $row->email,
                        'contact_address' => $row->contact_address,
                        'medical_record' => $row->medical_record,
                        'covid_diagnosed' => $row->covid_diagnosed,
                        'covid_close_contacts' => $row->covid_close_contacts,
                        'covid_date' => $row->covid_date,
                        'height' => $row->height,
                        'weight' => $row->weight,
                        'blood_date' => $row->blood_date->format('Y-m-d'),
                        'blood_time' => $row->blood_time,
                        'district' => $row->district,
                        'address' => $row->address,
                        'report' => $row->report,
                        'report_explanation' => $row->report_explanation,
                        'remark' => $row->remark,
                        'paid' => $row->paid,
                        'customer_history_details' => $row->customer_history_details->map(function ($row) {
                            return [
                                'id' => $row->id,
                                'title' => $row->title,
                                'price' => $row->price,
                            ];
                        })

                    ];
                }),
            ]
        ];
    }
}
