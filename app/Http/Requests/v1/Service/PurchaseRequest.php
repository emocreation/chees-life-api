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
            'name' => 'nullable|string',
            'gender' => 'nullable|in:F,M',
            'birthday' => 'nullable|date',
            'hkid' => 'nullable|string|max:32',
            'tel' => 'nullable|string',
            'email' => 'nullable|email',
            'password' => 'nullable|string',

            'service_id' => 'required|int|exists:services,id',
            'district_id' => 'required|int|exists:districts,id',
            'contact_address' => 'nullable|string',
            'medical_record' => 'nullable|string',
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
