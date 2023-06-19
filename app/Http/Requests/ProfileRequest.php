<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:160'],
            'location' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5048'],
            'banner' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5048'],
            'bannerNull' => ['nullable', 'string', 'max:30'],
        ];
    }
}
