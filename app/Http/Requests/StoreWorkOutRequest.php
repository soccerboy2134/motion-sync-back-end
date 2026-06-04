<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreWorkOutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
     public function rules(): array
    {
        // return [
        //     'user_id' => 'required|exists:users',
        //     'length' => 'required',
        //     'speed' => 'required',
        //     'type' => 'required',
        //     'points' => 'required',
        // ];
        return [
            'waypoints' => 'required|array',
            
            // Im honestly not sure if this fully works, but I pretend it does and Laravel agrees with me so..
            'waypoints.*' => [
                'required',
                'array:lat,lon,timestamp',
            ],

            'waypoints.*.lat' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'waypoints.*.lon' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'waypoints.*.timestamp' => [
                'required',
            ],
        ];
    }

    
    public function messages(): array
    {
        return [
            'waypoints.required' => 'Waypoints array not supplied',
            'waypoints.array' => 'Waypoints is not an array',
        ];
        // return [
        //     'user_id.required' => 'Name is required',
        //     'length.required' => 'length is required',
        //     'speed.required' => 'speed is required',
        //     'type.required' => 'type is required',
        //     'points.required' => 'points is required',
        // ];
    }
}
