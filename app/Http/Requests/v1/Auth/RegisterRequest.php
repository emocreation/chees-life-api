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
            'id_type' => 'required|string|in:hkid,cnid,passport,other',
            'id_type_other' => 'nullable|string|required_if:id_type,other',
            'hkid' => 'required|string|max:32',
            'tel' => 'required|string',
            'email' => 'required|string|unique:customers,email',
            'password' => 'required|string',
        ];
    }
}
