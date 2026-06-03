<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AuthenticateUserRequest extends FormRequest
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
            'display_name' => 'required',
            'password' => 'required|string|min:1',
        ];
    }

    public function messages()
    {
        return [
            'display_name.required' => 'Email is required',
            'password.required' => 'Password is required',
        ];
    }
}
