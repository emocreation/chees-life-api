<?php

namespace App\Http\Requests\SocialMedia;

use App\Enums\SocialMedia;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->can('update#social_media');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //'type' => [new Enum(SocialMedia::class)],
            'link' => 'nullable|string',
            'enable' => 'required|bool'
        ];
    }
}
