<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTweetRequest extends FormRequest
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
            'content' => 'required|string|max:280',
            'reply_to' => 'nullable|integer',
            'selectedImage' => 'nullable|array',
            'selectedImage.*' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,webm,mp3,wav|max:100000'
        ];
    }
}