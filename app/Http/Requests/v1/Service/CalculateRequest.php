<?php

namespace App\Http\Requests\v1\Service;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CalculateRequest extends FormRequest
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
            'service_id' => 'required|int|exists:services,id',
            //'district_id' => 'required|int|exists:districts,id',
            //'report_explanation' => 'required|in:na,by_phone,by_appointment',
            'code' => 'required|string',
        ];
    }
}
