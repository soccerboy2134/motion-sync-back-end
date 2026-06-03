<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkOutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
     public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users',
            'length' => 'required',
            'speed' => 'required',
            'type' => 'required',
            'points' => 'required',
        ];
    }

    
    public function messages(): array
    {
        return [
            'user_id.required' => 'Name is required',
            'length.required' => 'length is required',
            'speed.required' => 'speed is required',
            'type.required' => 'type is required',
            'points.required' => 'points is required',
        ];
    }
}
