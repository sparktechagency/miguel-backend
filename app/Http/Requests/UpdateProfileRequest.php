<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            // 'full_name'=>'nullable|string|min:3,max:255',
            // 'avatar'=>'nullable|image|mimes:png,jpg,jpeg,svg|max:10240',// Max 10MB
            // 'contact'  => 'nullable|string|max:255',
            // 'location' => 'nullable|string|max:255',

            'full_name'=>'nullable|string|min:3,max:255',
            'avatar'=>'nullable|image|mimes:png,jpg,jpeg,svg|max:10240',// Max 10MB
            'contact'  => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ];
    }
}
