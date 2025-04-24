<?php

namespace Dcodegroup\LaravelChat\Http\Requests\Chat;

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
            'chattable_id' => ['required', 'string'],
            'chattable_type' => ['required', 'string'],
        ];
    }
}
