<?php

namespace App\Http\Requests\LatestNew;

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
        return auth()->user() && auth()->user()->can('create#latest_new');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return RuleFactory::make([
            'news_date' => 'required|date',
            'enable' => 'required|bool',
            'en' => 'required|array',
            'tc' => 'required|array',
            '%title%' => 'required|string',
            '%introduction%' => 'nullable|string',
            '%description%' => 'nullable|string',
            'image' => 'nullable|image',
        ]);
    }
}
