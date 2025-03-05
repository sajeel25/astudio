<?php

namespace App\Http\Requests;

use App\Models\Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectRequest extends FormRequest
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
        $project = $this->route('project') ?: $this->input('id');
        $projectId = $project?->id;
        $rules = [

            "name" => "sometimes|unique:projects,name," . ($projectId ?? "NULL") . ",id",
            "status" => "nullable|in:Active,In-Active",
            "user_id" => "nullable|array",
            "user_id.*" => "exists:users,id",
            'attributes' => 'nullable|array',
            'attributes.*.id' => 'exists:attributes,id',
            "attributes.*.value" => ["required", function ($attribute, $value, $fail) {
                $attributeId = $this->input(str_replace('.value', '.id', $attribute));

                if ($attributeId) {
                    $attribute = Attribute::find($attributeId);

                    if (!$attribute) {
                        return;
                    }

                    switch ($attribute->type) {
                        case 'text':
                            if (!is_string($value) || strlen($value) > 255) {
                                $fail("The value for attribute {$attribute->name} must be a string with a maximum length of 255 characters.");
                            }
                            break;
                        case 'number':
                            if (!is_numeric($value)) {
                                $fail("The value for attribute {$attribute->name} must be a valid number.");
                            }
                            break;
                        case 'date':
                            if (!strtotime($value)) {
                                $fail("The value for attribute {$attribute->name} must be a valid date.");
                            }
                            break;
                        case 'select':
                            $allowedOptions = $attribute->options ?? [];
                            if (!in_array($value, $allowedOptions)) {
                                $fail("The selected value for attribute {$attribute->name} is invalid.");
                            }
                            break;
                    }
                }
            }]

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
            'name.required' => "The project name is required",
            'name.unique' =>  "The project name already exists",
            'status.in' => "The status can either be Active or In-Active",
            'user_id.array' => "The user_id must be array"
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
