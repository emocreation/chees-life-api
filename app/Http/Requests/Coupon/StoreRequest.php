<?php

namespace App\Http\Requests\Coupon;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->can('create#coupon');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return RuleFactory::make([
            '%name%' => 'required|string',
            'type' => 'required|in:amount,percentage',
            'limitation' => 'nullable|numeric',
            'value' => ['required', 'numeric', 'min:0', Rule::when($this->input('type') === 'percentage', 'between:0,100')],
            'code' => 'required|string',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date',
            'quota' => 'required|numeric|min:0',
            'coupon_services.*' => 'nullable|numeric|exists:services,id',
        ]);
    }
}
