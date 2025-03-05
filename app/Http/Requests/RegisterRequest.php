<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
        $rules = [
            "first_name" => "required|string|max:255|regex:/^[a-zA-Z\s\-]+$/",
            'last_name' => "required|string|max:255|regex:/^[a-zA-Z\s\-]+$/",
            "email" => "required|email|unique:users",
            "password" => "required|min:8|confirmed",
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
            'first_name.required' => "The first name field is required",
            'first_name.string' =>  "The first name must be a string",
            'first_name.max' => "The first name cannot exceed 255 characters",
            'first_name.regex' => "The first name cannot contain special characters",
            'last_name.required' => "The last name field is required",
            'last_name.string' =>  "The last name must be a string",
            'last_name.max' => "The last name cannot exceed 255 characters",
            'last_name.regex' => "The last name cannot contain special characters",
            'email.email' =>  "Please enter a valid email address",
            'email.required' => "The email field is required",
            'email.email' =>  "Please enter a valid email address",
            'email.unique' =>  "This email address is already taken",
            'password.required' =>  "The password field is required",
            'password.min' => "The password field must contain at least 8 characters",
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
            'message' => "Validation Failed!",
            'errors' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
