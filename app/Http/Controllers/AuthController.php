<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNewPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OtpRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Mail\SendOtpMail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(RegisterRequest $registerRequest)
    {
        try {
            $validated = $registerRequest->validated();
            $validated['password']= bcrypt($validated['password']);
            $user = User::create($validated);
            $this->otpSend($user);
            return $this->sendResponse($user, 'User registered successfully and OTP sent.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    private function otpSend($user)
    {
        $user = User::find($user->id);
        if (!$user) {
            return $this->sendError("User not found.");
        }
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(2);
        $user->otp_verified_at = null;
        $user->save();

        Mail::to($user->email)->queue(new SendOtpMail($otp));
    }
    public function otpVerify(OtpRequest $otpRequest)
    {
        try {
            $validated = $otpRequest->validated();
            $user = User::where('email', $validated['email'])
                        ->where('otp', $validated['otp'])
                        ->where('otp_expires_at', '>', now())
                        ->first();
            if (!$user) {
                return $this->sendError("Invalid or expired OTP.", [], 401);
            }
            $user->otp_verified_at = now();
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->sendResponse([
                'user' => $user,
                'token' => $token
            ], "OTP verified successfully.");

        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function resendOtp(ResendOtpRequest $resendOtpRequest)
    {
        try {
            $validated = $resendOtpRequest->validated();
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return $this->sendError("User not found.");
            }
            $this->otpSend($user);
            $data = User::where('email', $validated['email'])->first();

            return $this->sendResponse($data, "OTP sent successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function login(LoginRequest $loginRequest)
    {
        try {
            $validated = $loginRequest->validated();
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return $this->sendError('Invalid email or password.');
            }
            if (!Hash::check($validated['password'], $user->password)) {
                return $this->sendError('Invalid email or password.');
            }
            if (is_null($user->otp_verified_at)) {
                return $this->sendError('Please verify your OTP before logging in.');
            }
            if ($user->is_banned == true) {
                return $this->sendError('You are banned. Kindly contact admin.');
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->sendResponse([
                'user' => $user,
                'token' => $token,
            ], ucfirst($user->full_name) . ' Login successful.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function createNewPassword(CreateNewPasswordRequest $createNewPasswordRequest)
    {
        try {
            $validated = $createNewPasswordRequest->validated();

            $user = Auth::user();
            if (!$user) {
                return $this->sendError("Unauthorized access. Please log in.", [], 401);
            }
            $user->password = bcrypt($validated['password']);
            $user->save();
            return $this->sendResponse([], "Password updated successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->sendError("Unauthorized access.", [], 401);
            }
            $validated = $request->validated();
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('uploads/avatar', $imageName, 'public');
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $validated['avatar'] = 'storage/'.$path;
            }
            $user->update($validated);
            return $this->sendResponse($user, "Profile updated successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function profile()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return $this->sendError('User not authenticated.', [], 401);
            }
            return $this->sendResponse($user, 'User profile fetched successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }

}
