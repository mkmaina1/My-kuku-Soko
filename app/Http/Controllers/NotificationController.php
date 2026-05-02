<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get custom notifications
        $notifications = $user->notifications()->orderBy('created_at', 'desc');

        // Filter by read status
        if ($request->has('filter')) {
            if ($request->filter === 'unread') {
                $notifications->where('read', false);
            } elseif ($request->filter === 'read') {
                $notifications->where('read', true);
            }
        }

        $notifications = $notifications->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Notification not found'], 404);
            }
            return back()->with('error', 'Notification not found.');
        }

        $notification->markAsRead();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'unread_count' => Auth::user()->unreadNotifications()->count()
            ]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Notification not found'], 404);
            }
            return back()->with('error', 'Notification not found.');
        }

        $notification->markAsUnread();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'unread_count' => Auth::user()->unreadNotifications()->count()
            ]);
        }

        return back()->with('success', 'Notification marked as unread.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        $user = Auth::user();

        // Update all unread notifications for the user
        Notification::where('user_id', $user->id)
            ->where('read', false)
            ->update(['read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return back()->with('error', 'Notification not found.');
        }

        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Delete all read notifications.
     */
    public function destroyAllRead()
    {
        $user = Auth::user();

        Notification::where('user_id', $user->id)
            ->where('read', true)
            ->delete();

        return back()->with('success', 'All read notifications deleted.');
    }

    /**
     * Clear all notifications for current user.
     */
    public function clearAll()
    {
        $user = Auth::user();

        Notification::where('user_id', $user->id)->delete();

        return back()->with('success', 'All notifications cleared.');
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get latest notifications.
     */
    public function getLatest()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'color' => $notification->badge_color,
                    'link' => $notification->link ?? '#',
                    'read' => $notification->read,
                    'time_ago' => $notification->time_ago,
                ];
            });

        return response()->json($notifications);
    }

    /**
     * Get notification statistics.
     */
    public function getStats()
    {
        $user = Auth::user();

        $total = $user->notifications()->count();
        $unread = $user->unreadNotifications()->count();
        $read = $user->readNotifications()->count();

        // Count by type
        $byType = Notification::where('user_id', $user->id)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        return response()->json([
            'total' => $total,
            'unread' => $unread,
            'read' => $read,
            'by_type' => $byType
        ]);
    }

    /**
     * Send a notification to a user.
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string',
            'link' => 'nullable|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $user = User::find($request->user_id);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'user_type' => $user->role ?? 'user',
            'type' => $request->type ?? 'system',
            'title' => $request->title,
            'message' => $request->message,
            'link' => $request->link,
            'icon' => $request->icon ?? 'fas fa-bell',
            'color' => $request->color ?? 'primary',
            'read' => false,
            'data' => [
                'sent_by' => Auth::id(),
                'sent_at' => now()->toDateTimeString(),
            ],
            'created_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'notification' => $notification
            ]);
        }

        return back()->with('success', 'Notification sent successfully.');
    }

    /**
     * Send notification to multiple users.
     */
    public function sendBulkNotification(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string',
            'link' => 'nullable|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $notifications = [];
        foreach ($request->user_ids as $userId) {
            $user = User::find($userId);

            $notifications[] = Notification::create([
                'user_id' => $userId,
                'user_type' => $user->role ?? 'user',
                'type' => $request->type ?? 'system',
                'title' => $request->title,
                'message' => $request->message,
                'link' => $request->link,
                'icon' => $request->icon ?? 'fas fa-bell',
                'color' => $request->color ?? 'primary',
                'read' => false,
                'data' => [
                    'sent_by' => Auth::id(),
                    'sent_at' => now()->toDateTimeString(),
                ],
                'created_by' => Auth::id(),
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => count($notifications)
            ]);
        }

        return back()->with('success', 'Notifications sent to ' . count($notifications) . ' users.');
    }
}
