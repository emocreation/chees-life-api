<?php

namespace App\Http\Requests\Service;

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
        return auth()->user() && auth()->user()->can('create#service');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return RuleFactory::make([
            'category_id' => 'required|int|exists:categories,id',
            'price' => 'required|int',
            'description_en' => 'nullable|string',
            'description_tc' => 'nullable|string',
            'detail_en' => 'nullable|string',
            'detail_tc' => 'nullable|string',
            'hot' => 'bool',
            'enable' => 'required|bool',
            'en' => 'required|array',
            'tc' => 'required|array',
            '%title%' => 'required|string',
            '%subtitle%' => 'nullable|string',
            /*'service_descriptions.*.%description%' => 'required|string',
            'service_details.*.%title%' => 'required|string',
            'service_details.*.%content%' => 'required|string',*/
            'image' => 'nullable|image',
        ]);
    }
}
