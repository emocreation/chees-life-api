<?php

namespace App\Http\Requests\CustomerHistory;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->can('update#customer_history');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return RuleFactory::make([
            'customer_id' => 'nullable|exists:customers,id',
            'name' => 'required|string',
            'gender' => 'required|in:F,M',
            'birthday' => 'required|date',
            'hkid' => 'required|string',
            'tel' => 'required|string',
            'email' => 'required|string',
            'contact_address' => 'nullable|string',
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
            'paid' => 'bool',
            '%district%' => 'required|string',
            '%subtitle%' => 'nullable|string',
            'customer_history_details.*.price' => 'required|string',
            'customer_history_details.*.%title%' => 'required|string',
            'report_pdf' => 'file|mimes:pdf',
        ]);
    }
}
