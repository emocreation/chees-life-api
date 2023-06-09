<?php

namespace App\Http\Requests\Timeslot;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->can('create#timeslot');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'time_from' => 'required|date_format:H:i:s',
            'time_to' => 'required|date_format:H:i:s',
            'interval' => 'required|int',
            'enable' => 'required|bool',
            'quota' => 'required|int',
        ];
    }
}
