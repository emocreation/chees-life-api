<?php

namespace App\Http\Requests\Common;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam('rows', 'array')]
#[BodyParam('rows[].id', 'int', 'The id of the record')]
#[BodyParam('rows[].sequence', 'int', 'The sequence of the record')]
class DragUpdateRequest extends FormRequest
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
            'rows.*.id' => 'required|int',
            'rows.*.sequence' => 'required|int',
        ];
    }
}
