<?php

namespace App\Http\Requests\User;

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
        return auth()->user() && auth()->user()->can('update#user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        return [
            'name' => ['required', 'string', Rule::unique('users')->ignore($user->id ?? '')],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id ?? '')],
            'password' => ['nullable', 'string'],
            'role_name' => ['required', 'string', 'exists::roles,name'],
        ];
    }
}
