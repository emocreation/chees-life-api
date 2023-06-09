<?php

namespace App\Http\Requests\Service;

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
        return auth()->user() && auth()->user()->can('update#service');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return RuleFactory::make([
            'price' => 'required|int',
            'hot' => 'bool',
            'enable' => 'required|bool',
            'en' => 'required|array',
            'tc' => 'required|array',
            '%title%' => 'required|string',
            '%subtitle%' => 'nullable|string',
            'service_descriptions.*.id' => 'nullable|int',
            'service_descriptions.*.sequence' => 'nullable|int',
            'service_descriptions.*.%description%' => 'required|string',
            'service_details.*.id' => 'nullable|int',
            'service_details.*.sequence' => 'nullable|int',
            'service_details.*.%title%' => 'required|string',
            'service_details.*.%content%' => 'required|string',
            'image' => 'nullable|image',
        ]);
    }
}
