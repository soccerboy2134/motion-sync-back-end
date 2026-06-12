<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // It's fine to let the user re-log if they are already logged in; we remove the old bearer token anyway
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    { 
        return [
            'name' => 'required',
            'display_name' => 'required|unique:users',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'visibility' => 'sometimes|boolean',
            'password' => 'required|min:1',
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'display_name.required' => 'Display name is required',
            'display_name.unique' => 'Display name must be unique',
            'gender.required' => 'Gender is required',
            'date_of_birth.required' => 'Date of birth is required',
            'date_of_birth.date' => 'Date of birth must be a valid date',
            'visibility.boolean' => 'Visibility must be a boolean value',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters long',
            'password_confirmation.required' => 'Password confirmation is required',
            'password_confirmation.same' => 'Password confirmation must match the password',
        ];
    }
}
