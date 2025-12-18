<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SongRequest extends FormRequest
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
            'title' => 'required|string|min:3',
            'song' => 'nullable|file|mimetypes:audio/mpeg,audio/mp3|max:1024000', // 1GB
            'song_poster' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:10240', // 10MB

            // Multiple files validation
            'midi_file' => 'nullable|array',
            'midi_file.*' => 'file|mimetypes:audio/midi,audio/x-midi|max:1024000', // each file 1GB

            'web_vocals' => 'nullable|array',
            'web_vocals.*' => 'file|mimetypes:audio/mpeg,audio/mp3|max:1024000',

            'dry_vocals' => 'nullable|array',
            'dry_vocals.*' => 'file|mimetypes:audio/mpeg,audio/mp3|max:1024000',

            'lyrics' => 'nullable|file|mimetypes:application/pdf,text/plain|max:10240',

            'artist_id' => 'required|exists:artists,id',
            'genre_id' => 'required|exists:genres,id',
            'bpm' => 'required|numeric|min:30|max:1000',
            'key_id' => 'required|exists:keys,id',
            'license_id' => 'required|exists:licenses,id',
            'type_id' => 'required|exists:types,id',
            'gender' => 'required|in:male,female,other',
            'price' => 'nullable|numeric',
            'is_published' => 'boolean',
        ];
    }
}
