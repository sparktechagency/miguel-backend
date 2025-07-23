<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIntentRequest;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function createPaymentIntent(CreateIntentRequest $CreateIntentRequest)
    {
        $validated = $CreateIntentRequest->validated();
        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $validated['amount']*100,
                'currency' => 'usd',
                'payment_method' => $validated['payment_method'],
            ]);
            return $this->sendResponse($paymentIntent, 'Payment intent created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Payment failed.'. $e->getMessage(),[], 500);
        }
    }
}
