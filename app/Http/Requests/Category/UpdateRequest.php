<?php

namespace App\Http\Requests\Category;

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
        return auth()->user() && auth()->user()->can('update#category');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return RuleFactory::make([
            'en' => 'required|array',
            'tc' => 'required|array',
            '%name%' => 'required|string',
            '%title%' => 'required|string',
            '%description%' => 'required|string',
            'image' => 'nullable|image',
            'youtube' => 'nullable|string',
            'enable' => 'required|bool',
        ]);
    }
}
