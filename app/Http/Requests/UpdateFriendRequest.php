<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFriendRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'status' => [
                'required',
                Rule::in([
                    'accepted',
                    'denied',
                    'unfriend',
                    'block',
                    'unblock',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => "user_id is required.",
            'user_id.exists' => "We can't find the user.",
            'status.required' => 'status is required.',
            'status.string' => 'Status is not a string.',
        ];
    }
}
