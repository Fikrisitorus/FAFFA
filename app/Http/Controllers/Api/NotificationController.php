<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        $user = Auth::user();
        
        $count = Notification::where('user_id', $user->id)
            ->unread()
            ->count();
        
        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Get recent notifications
     */
    public function recent(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);
        
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->limit($limit)
            ->get();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::where('user_id', $user->id)->unread()->count()
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'unread_count' => Notification::where('user_id', $user->id)->unread()->count()
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        return response()->json([
            'success' => true,
            'unread_count' => 0
        ]);
    }

    /**
     * Check for new notifications (for polling)
     */
    public function checkNew(Request $request)
    {
        $user = Auth::user();
        $lastCheck = $request->get('last_check', now()->subMinutes(5)->toDateTimeString());
        
        $newNotifications = Notification::where('user_id', $user->id)
            ->where('created_at', '>', $lastCheck)
            ->latest()
            ->get();
        
        return response()->json([
            'has_new' => $newNotifications->count() > 0,
            'notifications' => $newNotifications,
            'unread_count' => Notification::where('user_id', $user->id)->unread()->count(),
            'last_check' => now()->toDateTimeString()
        ]);
    }
}


