<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'songs.*.song_id.required' => 'Each song must have a song ID.',
            'songs.*.song_id.exists' => 'One or more song IDs are invalid.',
            'songs.*.price.required' => 'Each song must have a price.',
            'songs.*.is_midifile.required' => 'Each song must have a midifile.',
            'payment_method' => 'required|in:card,paypal',
            'order_status' =>'nullable|in:pending,completed,failed',

        ];
    }
}
