<?php

namespace Dcodegroup\DCodeChat\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'chatable_id' => ['required', 'string'],
            'chatable_type' => ['required', 'string'],
        ];
    }
}
