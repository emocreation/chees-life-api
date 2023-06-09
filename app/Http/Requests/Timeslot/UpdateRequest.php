<?php

namespace App\Http\Requests\Timeslot;

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
        return auth()->user() && auth()->user()->can('update#timeslot');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'available_date' => 'required|date',
            'enable' => 'required|bool',
            'timeslot_quotas' => 'required|array',
            'timeslot_quotas.*.id' => 'nullable|int',
            'timeslot_quotas.*.from' => 'required|date_format:H:i:s',
            'timeslot_quotas.*.to' => 'required|date_format:H:i:s',
            'timeslot_quotas.*.quota' => 'required|int',
        ];
    }
}
