<?php

namespace App\Http\Requests\District;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->can('create#district');
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
