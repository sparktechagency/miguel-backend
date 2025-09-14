<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArtistRequest extends FormRequest
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
            'description' => 'nullable|string',
            'profile' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
            'gender' => 'required|in:male,female,other',
            'singer' => 'nullable|string|max:255',
            'singer_writer' => 'nullable|string|max:255',
            'cover_song' => 'nullable|file|mimetypes:audio/mpeg,audio/mp3|max:1024000',
            'location' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
        ];
    }
}
