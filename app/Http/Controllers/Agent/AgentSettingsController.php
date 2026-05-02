<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AgentSetting;
use App\Models\PerformanceTarget;
use App\Models\Notification;

class AgentSettingsController extends Controller
{
    /**
     * Display agent settings.
     */
    public function index(Request $request)
{
    $agent = Auth::user();
    $settings = AgentSetting::firstOrCreate(['agent_id' => $agent->id]);
    $performanceTargets = PerformanceTarget::where('agent_id', $agent->id)
        ->where('status', 'active')
        ->get();

    // Get notifications for the agent - 4 per page
    $perPage = 4;
    $notifications = Notification::where('user_id', $agent->id)
        ->where('user_type', 'agent')
        ->latest()
        ->paginate($perPage);

    // Get unread count for badges
    $unreadCount = Notification::where('user_id', $agent->id)
        ->where('user_type', 'agent')
        ->where('read', false)
        ->count();

    // Check if it's an AJAX request for notifications partial
    if ($request->ajax() && $request->has('partial') && $request->partial === 'notifications') {
        // Return only the notifications partial HTML
        $html = view('agent.settings.partials.notifications-list', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'unread_count' => $unreadCount,
            'current_page' => $notifications->currentPage(),
            'last_page' => $notifications->lastPage()
        ]);
    }

    return view('agent.settings.index', compact(
        'agent',
        'settings',
        'performanceTargets',
        'notifications',
        'unreadCount'
    ));
}

    /**
     * Update business information.
     */
    public function updateBusinessInfo(Request $request)
    {
        $agent = Auth::user();

        $validated = $request->validate([
            'business_name' => ['nullable', 'string', 'max:255'],
            'business_registration_number' => ['nullable', 'string', 'max:100'],
            'tax_identification_number' => ['nullable', 'string', 'max:100'],
            'business_address' => ['nullable', 'string', 'max:500'],
            'business_phone' => ['nullable', 'string', 'max:20'],
            'business_email' => ['nullable', 'email', 'max:255'],
            'business_website' => ['nullable', 'url', 'max:255'],
            'business_description' => ['nullable', 'string', 'max:1000'],
        ]);

        $settings = AgentSetting::updateOrCreate(
            ['agent_id' => $agent->id],
            $validated
        );

        // Create notification for business info update
        Notification::create([
            'user_id' => $agent->id,
            'user_type' => 'agent',
            'type' => 'system',
            'title' => 'Business Information Updated',
            'message' => 'Your business information has been successfully updated.',
            'data' => [
                'action' => 'business_info_update',
                'updated_fields' => array_keys($validated)
            ]
        ]);

        return back()->with('success', 'Business information updated successfully.');
    }

    /**
     * Update notification settings.
     */
    public function updateNotifications(Request $request)
    {
        $agent = Auth::user();

        $validated = $request->validate([
            'email_notifications' => ['boolean'],
            'sms_notifications' => ['boolean'],
            'order_updates' => ['boolean'],
            'commission_alerts' => ['boolean'],
            'target_alerts' => ['boolean'],
            'marketplace_updates' => ['boolean'],
        ]);

        $settings = AgentSetting::updateOrCreate(
            ['agent_id' => $agent->id],
            $validated
        );

        // Create notification for settings update
        Notification::create([
            'user_id' => $agent->id,
            'user_type' => 'agent',
            'type' => 'system',
            'title' => 'Notification Settings Updated',
            'message' => 'Your notification settings have been successfully updated.',
            'data' => [
                'action' => 'notification_settings_update',
                'updated_fields' => array_keys($validated)
            ]
        ]);

        return back()->with('success', 'Notification settings updated successfully.');
    }

    /**
     * Update working hours.
     */
    public function updateWorkingHours(Request $request)
    {
        $agent = Auth::user();

        $validated = $request->validate([
            'working_days' => ['nullable', 'string', 'max:100'],
            'working_hours_start' => ['nullable', 'date_format:H:i'],
            'working_hours_end' => ['nullable', 'date_format:H:i'],
        ]);

        $settings = AgentSetting::updateOrCreate(
            ['agent_id' => $agent->id],
            $validated
        );

        // Create notification for working hours update
        Notification::create([
            'user_id' => $agent->id,
            'user_type' => 'agent',
            'type' => 'system',
            'title' => 'Working Hours Updated',
            'message' => 'Your working hours have been successfully updated.',
            'data' => [
                'action' => 'working_hours_update',
                'working_days' => $validated['working_days'] ?? null,
                'working_hours' => [
                    'start' => $validated['working_hours_start'] ?? null,
                    'end' => $validated['working_hours_end'] ?? null,
                ]
            ]
        ]);

        return back()->with('success', 'Working hours updated successfully.');
    }

    /**
     * Create or update performance target.
     */
    public function updatePerformanceTarget(Request $request)
    {
        $agent = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'target_type' => ['required', 'in:sales,orders,farmers,completion_rate,revenue,custom'],
            'target_value' => ['required', 'numeric', 'min:0'],
            'period' => ['required', 'in:daily,weekly,monthly,quarterly,yearly'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['agent_id'] = $agent->id;
        $validated['status'] = 'active';

        $action = 'created';
        if ($request->has('target_id')) {
            $target = PerformanceTarget::where('id', $request->target_id)
                ->where('agent_id', $agent->id)
                ->firstOrFail();
            $target->update($validated);
            $message = 'Performance target updated successfully.';
            $action = 'updated';
        } else {
            $target = PerformanceTarget::create($validated);
            $message = 'Performance target created successfully.';
        }

        // Create notification for target action
        Notification::create([
            'user_id' => $agent->id,
            'user_type' => 'agent',
            'type' => 'target',
            'title' => 'Performance Target ' . ucfirst($action),
            'message' => "Your performance target '{$validated['name']}' has been {$action}.",
            'data' => [
                'action' => 'target_' . $action,
                'target_id' => $target->id,
                'target_name' => $validated['name'],
                'target_type' => $validated['target_type'],
                'target_value' => $validated['target_value'],
            ]
        ]);

        return back()->with('success', $message);
    }

    /**
     * Delete performance target.
     */
    public function deletePerformanceTarget(Request $request, $id)
    {
        $agent = Auth::user();

        $target = PerformanceTarget::where('id', $id)
            ->where('agent_id', $agent->id)
            ->firstOrFail();

        $targetName = $target->name;
        $target->update(['status' => 'cancelled']);

        // Create notification for target deletion
        Notification::create([
            'user_id' => $agent->id,
            'user_type' => 'agent',
            'type' => 'target',
            'title' => 'Performance Target Deleted',
            'message' => "Your performance target '{$targetName}' has been deleted.",
            'data' => [
                'action' => 'target_deleted',
                'target_id' => $id,
                'target_name' => $targetName,
            ]
        ]);

        return back()->with('success', 'Performance target deleted successfully.');
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationAsRead(Request $request, $id)
    {
        $agent = Auth::user();

        $notification = Notification::where('id', $id)
            ->where('user_id', $agent->id)
            ->where('user_type', 'agent')
            ->firstOrFail();

        $notification->update(['read' => true]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsAsRead(Request $request)
    {
        $agent = Auth::user();

        Notification::where('user_id', $agent->id)
            ->where('user_type', 'agent')
            ->where('read', false)
            ->update(['read' => true]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete notification.
     */
    public function deleteNotification(Request $request, $id)
    {
        $agent = Auth::user();

        $notification = Notification::where('id', $id)
            ->where('user_id', $agent->id)
            ->where('user_type', 'agent')
            ->firstOrFail();

        $notification->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted'
            ]);
        }

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Clear all notifications.
     */
    public function clearAllNotifications(Request $request)
    {
        $agent = Auth::user();

        $deleted = Notification::where('user_id', $agent->id)
            ->where('user_type', 'agent')
            ->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications cleared',
                'deleted_count' => $deleted
            ]);
        }

        return back()->with('success', 'All notifications cleared.');
    }

    /**
     * Bulk notification actions.
     */
    public function bulkNotificationAction(Request $request)
    {
        $agent = Auth::user();
        $action = $request->input('bulk_action');
        $notificationIds = $request->input('notification_ids', []);

        if (empty($notificationIds)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No notifications selected.'
                ], 400);
            }
            return back()->with('error', 'No notifications selected.');
        }

        $notifications = Notification::whereIn('id', $notificationIds)
            ->where('user_id', $agent->id)
            ->where('user_type', 'agent')
            ->get();

        $count = 0;
        foreach ($notifications as $notification) {
            switch ($action) {
                case 'mark_read':
                    $notification->update(['read' => true]);
                    $count++;
                    break;
                case 'mark_unread':
                    $notification->update(['read' => false]);
                    $count++;
                    break;
                case 'delete':
                    $notification->delete();
                    $count++;
                    break;
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} notification(s) updated successfully.",
                'count' => $count
            ]);
        }

        return back()->with('success', "{$count} notification(s) updated successfully.");
    }

    /**
     * Get notification counts for AJAX updates.
     */
    public function getNotificationCounts(Request $request)
    {
        $agent = Auth::user();

        $unreadCount = Notification::where('user_id', $agent->id)
            ->where('user_type', 'agent')
            ->where('read', false)
            ->count();

        $totalCount = Notification::where('user_id', $agent->id)
            ->where('user_type', 'agent')
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'total_count' => $totalCount
        ]);
    }

    /**
     * Helper methods for target display
     */
    public static function getTargetColor($percentage)
    {
        if ($percentage >= 100) return 'success';
        if ($percentage >= 70) return 'info';
        if ($percentage >= 40) return 'warning';
        return 'danger';
    }

    public static function getTargetUnit($targetType)
    {
        switch($targetType) {
            case 'sales':
            case 'revenue':
                return 'KES';
            case 'completion_rate':
                return '%';
            default:
                return '';
        }
    }

    public static function getTargetIcon($targetType)
    {
        switch($targetType) {
            case 'sales':
                return 'money-bill-wave';
            case 'orders':
                return 'shopping-cart';
            case 'farmers':
                return 'users';
            case 'completion_rate':
                return 'check-circle';
            case 'revenue':
                return 'chart-line';
            default:
                return 'bullseye';
        }
    }

    /**
     * Helper to create notifications for agents (optional, can be used elsewhere)
     */
    public static function createAgentNotification($agentId, $type, $title, $message, $data = [])
    {
        return Notification::create([
            'user_id' => $agentId,
            'user_type' => 'agent',
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'read' => false,
        ]);
    }
}
