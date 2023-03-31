<?php

namespace App\Http\Requests\v1;

use App\Traits\DefaultResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateAdminRequest extends FormRequest
{
    use DefaultResponse;
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
            'avatar' => 'nullable',
            'address' => 'required',
            'phone_number' => 'required',
            'marketing' => 'nullable',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->jsonResponse([], 422, 0, 'Failed Validation', $validator->messages()->get('*')));
    }
}
