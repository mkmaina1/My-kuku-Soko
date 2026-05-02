<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get Laravel's notifications
        $notifications = $user->notifications()->orderBy('created_at', 'desc');

        if ($request->has('filter')) {
            if ($request->filter === 'unread') {
                $notifications->whereNull('read_at');
            } elseif ($request->filter === 'read') {
                $notifications->whereNotNull('read_at');
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

        // Mark as unread by setting read_at to null
        $notification->update(['read_at' => null]);

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
        Auth::user()->unreadNotifications->markAsRead();

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
        Auth::user()->notifications()->whereNotNull('read_at')->delete();

        return back()->with('success', 'All read notifications deleted.');
    }

    /**
     * Clear all notifications for current user.
     */
    public function clearAll()
    {
        Auth::user()->notifications()->delete();

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
                $data = $notification->data;

                return [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? 'Notification',
                    'message' => $data['message'] ?? '',
                    'icon' => $data['icon'] ?? 'fas fa-bell',
                    'color' => $data['color'] ?? 'primary',
                    'link' => $data['link'] ?? '#',
                    'read' => !is_null($notification->read_at),
                    'time_ago' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json($notifications->values()->all());
    }

    /**
     * Get notification statistics.
     */
    public function getStats()
    {
        $user = Auth::user();

        $total = $user->notifications()->count();
        $unread = $user->unreadNotifications()->count();
        $read = $user->notifications()->whereNotNull('read_at')->count();

        // Count by notification type from data field
        $byType = $user->notifications()
            ->get()
            ->groupBy(function($notification) {
                return $notification->data['type'] ?? 'other';
            })
            ->map->count();

        return response()->json([
            'total' => $total,
            'unread' => $unread,
            'read' => $read,
            'by_type' => $byType
        ]);
    }
}
