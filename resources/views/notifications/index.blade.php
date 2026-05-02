@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bell mr-2"></i>Notifications
                    </h3>
                    <div class="card-tools">
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-check-double mr-1"></i> Mark All as Read
                                </button>
                            </form>
                        @endif

                        <!-- Add filter dropdown -->
                        <div class="btn-group ml-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                Filter
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('notifications.index') }}">All</a>
                                <a class="dropdown-item" href="{{ route('notifications.index', ['filter' => 'unread']) }}">Unread</a>
                                <a class="dropdown-item" href="{{ route('notifications.index', ['filter' => 'read']) }}">Read</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    @foreach($notifications as $notification)
                                        @php
                                            // For CUSTOM notifications, get data from notification fields
                                            $title = $notification->title;
                                            $message = $notification->message;
                                            $icon = $notification->icon ?? ($notification->read ? 'far fa-bell' : 'fas fa-bell');
                                            $color = $notification->badge_color;
                                            $link = $notification->link ?? '#';
                                            $type = $notification->type;

                                            // Get data from custom data field (if exists)
                                            $data = $notification->data;
                                            $adminNotes = $data['admin_notes'] ?? $data['notes'] ?? null;
                                            $userName = $data['user_name'] ?? null;

                                            // For verification request notifications
                                            if ($type === 'verification_request' || $type === 'new_verification_request') {
                                                $title = 'New Verification Request';
                                                $message = ($userName ?? 'A user') . ' has submitted a verification request.';
                                                $link = $notification->link ?? '/admin/verifications';
                                                $icon = 'fas fa-shield-alt';
                                                $color = 'warning';
                                            }

                                            $time = \Carbon\Carbon::parse($notification->created_at);
                                            $isRead = $notification->read;
                                        @endphp
                                        <tr class="{{ $isRead ? '' : 'bg-light' }}" data-id="{{ $notification->id }}">
                                            <td style="width: 50px; text-align: center;">
                                                <i class="{{ $icon }} fa-lg text-{{ $color }}"></i>
                                            </td>
                                            <td>
                                                <div>
                                                    @if($link && $link !== '#')
                                                        <a href="{{ $link }}" class="text-decoration-none">
                                                    @endif
                                                        <strong class="{{ $isRead ? '' : 'text-dark' }}">
                                                            {{ $title }}
                                                        </strong>
                                                        <p class="mb-1">{{ $message }}</p>
                                                    @if($link && $link !== '#')
                                                        </a>
                                                    @endif
                                                    <small class="text-muted">
                                                        <i class="far fa-clock mr-1"></i>
                                                        {{ $time->format('M d, Y h:i A') }}
                                                        ({{ $time->diffForHumans() }})
                                                    </small>
                                                    @if($adminNotes)
                                                        <div class="mt-2 alert alert-warning p-2">
                                                            <strong>Admin Notes:</strong> {{ $adminNotes }}
                                                        </div>
                                                    @endif
                                                    @if(config('app.debug'))
                                                        <div class="mt-1">
                                                            <small class="text-muted">
                                                                Type: {{ $type ?? 'Unknown' }} |
                                                                ID: {{ $notification->id }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td style="width: 120px;">
                                                <div class="btn-group">
                                                    @if(!$isRead)
                                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Mark as read">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('notifications.markAsUnread', $notification->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Mark as unread">
                                                                <i class="fas fa-undo"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this notification?')" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $notifications->links() }}
                            <div class="float-right">
                                <form action="{{ route('notifications.destroyAllRead') }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete all read notifications?')">
                                        <i class="fas fa-trash mr-1"></i> Delete All Read
                                    </button>
                                </form>
                                <form action="{{ route('notifications.clearAll') }}" method="POST" class="d-inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete ALL notifications? This cannot be undone.')">
                                        <i class="fas fa-trash-alt mr-1"></i> Clear All
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="far fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h4>No notifications</h4>
                            <p class="text-muted">You don't have any notifications yet.</p>
                            @if(request()->has('filter'))
                                <a href="{{ route('notifications.index') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-eye mr-1"></i> View All Notifications
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    tr[data-id] {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    tr[data-id]:hover {
        background-color: #f8f9fa !important;
    }
    .notification-badge {
        position: absolute;
        top: 0;
        right: 0;
        transform: translate(25%, -25%);
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Mark notification as read when clicked on the row
    $('tr[data-id]').on('click', function(e) {
        // Only trigger if not clicking on buttons or links
        if (!$(e.target).closest('button').length &&
            !$(e.target).closest('form').length &&
            !$(e.target).closest('a').length) {

            const notificationId = $(this).data('id');
            const isUnread = $(this).hasClass('bg-light'); // Check if it's unread (has bg-light class)

            if (isUnread) { // Only mark as read if it's unread
                markAsRead(notificationId, $(this));
            }
        }
    });

    // Handle mark as read button click
    $(document).on('submit', 'form[action*="markAsRead"]', function(e) {
        e.preventDefault();
        const form = $(this);
        const notificationId = form.attr('action').split('/').pop();
        const rowElement = form.closest('tr');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: {
                _token: form.find('input[name="_token"]').val()
            },
            success: function(response) {
                updateRowUI(rowElement, true);
                updateNotificationCount();
            },
            error: function(xhr) {
                console.error('Error marking notification as read:', xhr);
                alert('Error marking notification as read. Please try again.');
            }
        });
    });

    // Handle mark as unread button click
    $(document).on('submit', 'form[action*="markAsUnread"]', function(e) {
        e.preventDefault();
        const form = $(this);
        const notificationId = form.attr('action').split('/').pop();
        const rowElement = form.closest('tr');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: {
                _token: form.find('input[name="_token"]').val()
            },
            success: function(response) {
                updateRowUI(rowElement, false);
                updateNotificationCount();
            },
            error: function(xhr) {
                console.error('Error marking notification as unread:', xhr);
                alert('Error marking notification as unread. Please try again.');
            }
        });
    });

    function markAsRead(notificationId, rowElement) {
        $.ajax({
            url: '{{ route("notifications.markAsRead", ":id") }}'.replace(':id', notificationId),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                updateRowUI(rowElement, true);
                updateNotificationCount();
            },
            error: function(xhr) {
                console.error('Error marking notification as read:', xhr);
                alert('Error marking notification as read. Please try again.');
            }
        });
    }

    function updateRowUI(rowElement, isRead) {
        if (isRead) {
            // Mark as read
            rowElement.removeClass('bg-light');
            rowElement.find('strong').removeClass('text-dark');

            // Update icon
            const iconElement = rowElement.find('i.fa-lg');
            if (iconElement.hasClass('fas')) {
                iconElement.removeClass('fas').addClass('far');
            }

            // Replace mark as read button with mark as unread
            const markReadForm = rowElement.find('form[action*="markAsRead"]');
            if (markReadForm.length) {
                const notificationId = markReadForm.attr('action').split('/').pop();
                const newForm = `
                    <form action="{{ route('notifications.markAsUnread', ':id') }}".replace(':id', '${notificationId}')" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Mark as unread">
                            <i class="fas fa-undo"></i>
                        </button>
                    </form>
                `;
                markReadForm.replaceWith(newForm);
            }
        } else {
            // Mark as unread
            rowElement.addClass('bg-light');
            rowElement.find('strong').addClass('text-dark');

            // Update icon
            const iconElement = rowElement.find('i.fa-lg');
            if (iconElement.hasClass('far')) {
                iconElement.removeClass('far').addClass('fas');
            }

            // Replace mark as unread button with mark as read
            const markUnreadForm = rowElement.find('form[action*="markAsUnread"]');
            if (markUnreadForm.length) {
                const notificationId = markUnreadForm.attr('action').split('/').pop();
                const newForm = `
                    <form action="{{ route('notifications.markAsRead', ':id') }}".replace(':id', '${notificationId}')" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success" title="Mark as read">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                `;
                markUnreadForm.replaceWith(newForm);
            }
        }
    }

    function updateNotificationCount() {
        $.get('{{ route("notifications.unreadCount") }}', function(data) {
            // Update navbar badge
            const badge = $('.notification-count, .notification-badge');
            if (data.count > 0) {
                badge.text(data.count).removeClass('d-none').show();
            } else {
                badge.addClass('d-none').hide();
            }

            // Update card header button if needed
            const markAllButton = $('form[action*="markAllRead"]');
            if (data.count === 0 && markAllButton.length) {
                markAllButton.remove();
            }
        });
    }

    // Initialize notification count on page load
    updateNotificationCount();
});
</script>
@endpush
