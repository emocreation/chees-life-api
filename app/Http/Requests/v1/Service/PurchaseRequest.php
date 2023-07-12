<?php

namespace App\Http\Requests\v1\Service;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|int|exists:customers,id',
            'service_id' => 'required|int|exists:services,id',
            'district_id' => 'required|int|exists:districts,id',
            'name' => 'required|string',
            'gender' => 'required|in:F,M',
            'birthday' => 'required|date',
            'hkid' => 'required|string|max:32',
            'tel' => 'required|string',
            'email' => 'required|email',
            'medical_record' => 'required|string',
            'covid_diagnosed' => 'required|bool',
            'covid_close_contacts' => 'required|bool',
            'covid_date' => 'nullable|date',
            'height' => 'required|decimal:0,2|digits_between:0,3',
            'weight' => 'required|decimal:0,2|digits_between:0,3',
            'blood_date' => 'required|date',
            'blood_time' => 'required|string',
            'address' => 'required|string',
            'report' => 'required|in:email,doctor',
            'remark' => 'nullable|string',
        ];
    }
}
