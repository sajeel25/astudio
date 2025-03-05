<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class AttributeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $attribute = $this->route('attribute') ?: $this->input('id');
        $attributeId = $attribute?->id;
        $required = $attributeId ? 'sometimes' : 'required';
        $rules = [
            "name" => "$required|unique:attributes,name," . ($attributeId ?? "NULL") . ",id",
            "type" => "$required|in:text,number,date,select",
            "options" => [
            Rule::requiredIf($this->input('type') === 'select'),
                "array"
            ],
            "options.*" => [
                Rule::requiredIf($this->input('type') === 'select' && !$attributeId),
                "string",
                "max:255"
            ]
        ];

        return $rules;
    }

    /**
     * Get the custom error messages for validation failures.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => "The attribute name is required",
            'name.unique' =>  "The attribute name must be unique",
            'type.in' => "The accepted types are text, number, date and select",
            'options.required' => "The options field is required with type is select"
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = [
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => "Validation failed!",
            'errors' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
