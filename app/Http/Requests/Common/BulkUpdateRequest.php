<?php

namespace App\Http\Requests\Common;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam('ids', 'integer[]', 'The id of the record')]
#[BodyParam('enable', 'bool', 'Enable or disable the record')]
#[BodyParam('hot', 'bool', 'Update hot(Apply to service API only)')]
#[BodyParam('delete', 'bool', 'Delete selected record')]
class BulkUpdateRequest extends FormRequest
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
            'ids' => 'required|array',
            'ids.*' => 'required|int',
            'enable' => 'bool',
            'hot' => 'bool',
            'delete' => 'bool',
        ];
    }
}
