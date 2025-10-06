<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyforArtistRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\SubscribeRequest;
use App\Mail\ApplyforArtistMail;
use App\Mail\ContactMail;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\ApplyArtistNotification;
use App\Notifications\ContactNotification;

;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Notifications\SubcriptionNotification;

class ContactController extends Controller
{
   public function contact(ContactRequest $contactRequest)
    {
        try {
            $validated = $contactRequest->validated();
            $adminUser = User::where('role', 'admin')->first();
            if ($adminUser) {
                $adminUser->notify(new ContactNotification($validated));
            } else {
                return $this->sendError("Admin user not found", [], 404);
            }
            Mail::to(env('MAIL_FROM_ADDRESS'))->queue(new ContactMail($validated));
            return $this->sendResponse([], "Your message has been sent successfully.");
        } catch (\Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
  public function applyForArtist(ApplyforArtistRequest $contactRequest)
    {
        try {
            $validated = $contactRequest->validated();
            if ($contactRequest->hasFile('file')) {
                $path = $contactRequest->file('file')->store('artist_audio', 'public');
                $validated['file'] = asset('storage/' . $path);
            }
             $adminUser = User::where('role', 'admin')->first();
            if ($adminUser) {
                $adminUser->notify(new ApplyArtistNotification($validated));
            } else {
                return $this->sendError("Admin user not found", [], 404);
            }
            Mail::to(env('MAIL_FROM_ADDRESS'))->queue(new ApplyforArtistMail($validated));
            return $this->sendResponse([], "Your message has been sent successfully.");
        } catch (\Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function subscribe(SubscribeRequest $request)
    {
        try {
            $validated = $request->validated();
            $adminUser = User::where('role', 'admin')->first();
            if ($adminUser) {
                $adminUser->notify(new SubcriptionNotification($validated));
            } else {
                return $this->sendError("Admin user not found", [], 404);
            }
            $subscription = Subscription::create($validated);
            return $this->sendResponse($subscription, "Subscription successful.");
        } catch (\Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function getSubscribe(Request $request)
    {
        try {
            $subscriptions = Subscription::orderBy('id', 'desc')->paginate($requst->per_page ??10);

            return $this->sendResponse($subscriptions, "Subscriptions retrieved successfully.");
        } catch (\Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }

}
