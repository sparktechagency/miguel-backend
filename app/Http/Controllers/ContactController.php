<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Requests\SupportRequest;
use App\Mail\ContactMail;
use App\Mail\SupportMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function contact(ContactRequest $contactRequest)
    {
        try {
            $validated = $contactRequest->validated();

            Mail::to(env('MAIL_FROM_ADDRESS'))->queue(new ContactMail($validated));

            return $this->sendResponse([], "Your message has been sent successfully.");
        } catch (\Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }

}
