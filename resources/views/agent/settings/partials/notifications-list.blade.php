@if(isset($notifications) && count($notifications) > 0)
    <!-- Notification stats -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <small class="text-muted">
            Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }}
            of {{ $notifications->total() }} notifications
        </small>
        <small class="text-muted">
            Page {{ $notifications->currentPage() }} of {{ $notifications->lastPage() }}
        </small>
    </div>

    <div class="notification-list">
        @foreach($notifications as $notification)
            <div class="notification-item mb-3 p-3 border rounded {{ $notification->read ? 'bg-light' : 'bg-white border-primary' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            @if($notification->type == 'email')
                                <i class="fas fa-envelope text-primary me-2"></i>
                            @elseif($notification->type == 'sms')
                                <i class="fas fa-sms text-success me-2"></i>
                            @elseif($notification->type == 'order')
                                <i class="fas fa-shopping-cart text-info me-2"></i>
                            @elseif($notification->type == 'commission')
                                <i class="fas fa-money-bill-wave text-warning me-2"></i>
                            @elseif($notification->type == 'target')
                                <i class="fas fa-bullseye text-danger me-2"></i>
                            @elseif($notification->type == 'marketplace')
                                <i class="fas fa-store text-secondary me-2"></i>
                            @else
                                <i class="fas fa-bell text-muted me-2"></i>
                            @endif
                            <h6 class="mb-0 {{ $notification->read ? 'text-muted' : 'font-weight-bold' }}">
                                {{ $notification->title }}
                            </h6>
                            @if(!$notification->read)
                                <span class="badge badge-primary badge-pill ms-2">New</span>
                            @endif
                        </div>
                        <p class="mb-2 text-muted">{{ $notification->message }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                            </small>
                            <div>
                                @if(!$notification->read)
                                    <form action="{{ route('agent.settings.notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary mark-as-read-btn">
                                            <i class="fas fa-check me-1"></i>Mark as Read
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('agent.settings.notifications.delete', $notification->id) }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger delete-notification-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Bulk Actions -->
    <div class="mt-4 pt-3 border-top">
        <form action="{{ route('agent.settings.notifications.bulk-action') }}" method="POST" id="bulkNotificationForm">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-2">
                    <select class="form-control" name="bulk_action" id="bulkAction">
                        <option value="">Bulk Actions</option>
                        <option value="mark_read">Mark as Read</option>
                        <option value="mark_unread">Mark as Unread</option>
                        <option value="delete">Delete</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <button type="submit" class="btn btn-primary w-100" id="applyBulkAction">
                        <i class="fas fa-check me-1"></i>Apply
                    </button>
                </div>
            </div>
        </form>
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
        <h5>No Notifications</h5>
        <p class="text-muted">You don't have any notifications yet.</p>
    </div>
@endif
