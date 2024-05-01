<?php

namespace App\Http\Requests;

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
            'email'         => ['required', 'string', 'email', 'unique:users', 'min:6', 'max:255'],
            'password'      => ['required', 'string', 'confirmed', 'min:6', 'max:50'],
            'name'          => ['required', 'string', 'min:2', 'max:75'],
            'token'         => ['string', 'min:2'],
        ];
    }
}
