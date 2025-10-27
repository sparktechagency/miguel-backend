<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportRequest;
use App\Mail\SupportMail;
use App\Models\User;
use App\Notifications\SupportNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function support(SupportRequest $supportRequest)
    {
        try {
            $validated = $supportRequest->validated();

            Mail::to('support@tunem.com')->queue(new SupportMail($validated));

            $adminUser = User::where('role', 'admin')->first();
            if ($adminUser) {
                $adminUser->notify(new SupportNotification($validated));
            } else {
                return $this->sendError("Admin user not found", [], 404);
            }
            return $this->sendResponse([], "Your message has been sent successfully.");
        } catch (\Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
}
