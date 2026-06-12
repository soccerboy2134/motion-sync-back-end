<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreThemeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        return $user->role === 'admin';
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
            'bg' => 'required',
            'surface' => 'required',
            'primary' => 'required',
            'onPrimary' => 'required',
            'accent' => 'required',
            'text' => 'required',
            'muted' => 'required',
            'border' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'bg.required' => 'Background color is required',
            'surface.required' => 'Surface color is required',
            'primary.required' => 'Primary color is required',
            'onPrimary.required' => 'On primary color is required',
            'accent.required' => 'Accent color is required',
            'text.required' => 'Text color is required',
            'muted.required' => 'Muted color is required',
            'border.required' => 'Border color is required',
        ];
    }
}
