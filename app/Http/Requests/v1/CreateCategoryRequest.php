<?php

namespace App\Http\Requests\v1;

use App\Traits\DefaultResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCategoryRequest extends FormRequest
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
            'title' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            $this->jsonResponse(
                [],
                422,
                0,
                'Failed Validation',
                $validator->messages()->get('*')
            )
        );
    }
}
