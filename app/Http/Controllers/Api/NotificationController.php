<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
     public function notificationReadAll()
    {
        $user = Auth::user();
        if ($user->unreadNotifications->isNotEmpty()) {
            $user->unreadNotifications->markAsRead();
            return response()->json([
                'message' => 'All notifications marked as read.',
                'status' => true
            ]);
        }
        return response()->json([
            'message' => 'No unread notifications found.',
            'status' => false
        ]);
    }
    public function notifications(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 10);
        $notifications = $user->notifications()->paginate($perPage);
        return response()->json([
            'success' => true,
            'count' => $notifications->total(),
            'data' => $notifications,
        ]);
    }
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Notification not found.',
        ], 404);
    }
}
