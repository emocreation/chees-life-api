<?php

namespace App\Http\Requests\Role;

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
        $role = $this->route('role');
        return $role->name !== 'Super Admin' && auth()->user() && auth()->user()->can('update#role');
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
            'permissions.*' => 'string',
        ];
    }
}
