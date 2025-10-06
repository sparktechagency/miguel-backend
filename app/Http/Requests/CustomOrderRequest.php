<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'order_type' => 'required|in:Custom,Normal',
            'payment_method' => 'required|in:card,paypal',
            // 'order_status' =>'nullable|in:pending,completed,failed',
        ];
    }
}
