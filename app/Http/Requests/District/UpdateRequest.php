<?php

namespace App\Http\Requests\District;

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
        return auth()->user() && auth()->user()->can('update#district');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return RuleFactory::make([
            'extra_charge' => 'required|int',
            'en' => 'required|array',
            'tc' => 'required|array',
            '%name%' => 'required|string',
            'enable' => 'required|bool'
        ]);
    }
}
