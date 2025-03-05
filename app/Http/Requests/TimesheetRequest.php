<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TimesheetRequest extends FormRequest
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
        $timesheet = $this->route('timesheet') ?: $this->input('id');
        $timesheetId = $timesheet?->id;
        $required = $timesheetId ? "sometimes" : "required";

        $rules = [

            "task_name" => "$required|max:255",
            'project_id' =>  [
                "$required",'string',
                function ($attribute, $value, $fail) {

                    $non_exist = array_filter([$value], function ($item) {
                        return !DB::table('project_user')->where('project_id', $item)->where('user_id', Auth::id())->exists();
                    });
                    if (!empty($non_exist)) {
                        $fail(__("The project ids do not assign to you", ['projects' => implode(', ', $non_exist)]));
                    }
                },
            ],
            'date' => "$required|date|date_format:Y-m-d",
            'hours' => "$required|integer|min:1"
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
            'task_name.required' => "The task name is required",
            'task_name.max' =>  "The task name cannot exceed 255 characters",
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
