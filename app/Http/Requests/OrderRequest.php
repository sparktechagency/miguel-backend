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
            'songs' => 'required|array',
            'songs.*.song_id' => 'required|exists:songs,id',
            'songs.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:card,paypal',
            'order_status' =>'required|in:pending,completed,failed'
        ];
    }
}
