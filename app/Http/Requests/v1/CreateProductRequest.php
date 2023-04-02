<?php

namespace App\Http\Requests\v1;

use App\Traits\DefaultResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

final class CreateProductRequest extends FormRequest
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
            'category_uuid' => 'required|exists:categories,uuid',
            'title' => 'required',
            'price' => 'required|decimal:2',
            'description' => 'required',
            'metadata' => 'required|json',
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
