<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
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
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required',
            'password' => 'required',
        ];
    }
}
