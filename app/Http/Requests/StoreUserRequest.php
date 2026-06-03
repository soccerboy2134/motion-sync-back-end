<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !Auth::check();
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
            'visibility' => 'required|boolean',
            'password' => 'required|min:8',
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
            'visibility.required' => 'Visibility is required',
            'visibility.boolean' => 'Visibility must be a boolean value',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters long',
            'password_confirmation.required' => 'Password confirmation is required',
            'password_confirmation.same' => 'Password confirmation must match the password',
        ];
    }
}
