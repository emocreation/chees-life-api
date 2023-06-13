<?php

namespace App\Http\Requests\v1\Customer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'gender' => 'required|in:F,M',
            'birthday' => 'required|date',
            'hkid' => ['required', 'string', Rule::unique('customers')->ignore(auth()->user()->id ?? '')],
            'tel' => 'required|string',
            'password' => 'nullable|string',
            'address' => 'nullable|string',
        ];
    }
}
