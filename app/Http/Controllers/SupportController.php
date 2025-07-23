<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportRequest;
use App\Mail\SupportMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function support(SupportRequest $supportRequest)
    {
        try {
            $validated = $supportRequest->validated();

            Mail::to(env('MAIL_FROM_ADDRESS'))->queue(new SupportMail($validated));

            return $this->sendResponse([], "Your message has been sent successfully.");
        } catch (\Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
}
