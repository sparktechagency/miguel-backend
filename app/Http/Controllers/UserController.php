<?php

namespace App\Http\Controllers;

use App\Http\Requests\IsBannedRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function users(UserRequest $userRequest)
    {
        try {
            $search = $userRequest->validated()['search'] ?? null;

            $users = User::where('role','USER')->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                        ->where('last_name','like','%'.$search. '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })->get();

            return $this->sendResponse($users, 'Users fetched successfully');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function updateBannedStatus(IsBannedRequest $isBannedRequest,$userId)
    {
        try {
            $validated = $isBannedRequest->validated();
            $user = User::findOrFail($userId);
            $user->is_banned = $validated['is_banned'];
            $user->save();

            return $this->sendResponse($user, 'User banned status updated successfully');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }


}
