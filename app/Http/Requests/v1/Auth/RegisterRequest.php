<?php

namespace App\Http\Requests\v1\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'hkid' => 'required|string|unique:customers,hkid',
            'tel' => 'required|string',
            'email' => 'required|string|unique:customers,email',
            'password' => 'required|string',
        ];
    }
}
