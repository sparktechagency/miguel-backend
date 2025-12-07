<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyforArtistRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'social_link' => 'nullable|string|max:1000',
            'submit_demo' => 'nullable|string|max:1000',
            'referral' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'genres' => 'required|array',
            'genres.*' => 'string',
            'other_genre' => 'nullable|string|max:255',
            // 'file' => 'nullable|file|mimetypes:audio/mpeg,audio/mp3|max:1024000', //1GB

        ];
    }
}
