<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'name' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^09[0-9]{9}$/', 'min:11', 'max:11'],
            'avatar' => ['required', 'image', 'max:6144'],
            'email' => ['required', 'string', 'email', 'max:30', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
            'id_picture' => ['required', 'image', 'max:6144'],
            'id_type' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'The phone number must start with 09 and be 11 digits long.',
        ];
    }
}
