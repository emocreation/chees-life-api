<?php

namespace App\Http\Requests\Review;

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
        return auth()->user() && auth()->user()->can('create#review');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return RuleFactory::make([
            'review_date' => 'required|date',
            'rating' => 'required|int|min:0|max:5',
            'en' => 'required|array',
            'tc' => 'required|array',
            '%customer_name%' => 'required|string',
            '%content%' => 'required|string',
            'enable' => 'required|bool'
        ]);
    }
}
