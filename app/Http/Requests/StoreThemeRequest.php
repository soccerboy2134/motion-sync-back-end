<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreThemeRequest extends FormRequest
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
            'name' => 'required', 
            'colorMain' => 'required', 
            'colorAccent' => 'required', 
            'colorBackground' => 'required', 
            'colorButton' => 'required', 
            'colorText' => 'required', 
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'colorMain.required' => 'Main color is required',
            'colorAccent.required' => 'Accent color is required',
            'colorBackground.required' => 'Background color is required',
            'colorButton.required' => 'Button color is required',
            'colorText.required' => 'Text color is required',
        ];
    }
}
