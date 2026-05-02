@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
    $notifications = auth()->user()->notifications()->take(10)->get();
@endphp

<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
        <i class="far fa-bell"></i>
        @if($unreadCount > 0)
            <span class="badge badge-warning navbar-badge">{{ $unreadCount }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">{{ $unreadCount }} New Notifications</span>
        <div class="dropdown-divider"></div>

        @forelse($notifications as $notification)
            @php
                $data = $notification->data;
                $icon = $data['icon'] ?? 'fas fa-info-circle';
                $color = $data['color'] ?? 'info';
                $time = \Carbon\Carbon::parse($notification->created_at)->diffForHumans();
            @endphp

            <a href="{{ $data['link'] ?? '#' }}" class="dropdown-item notification-item" data-id="{{ $notification->id }}">
                <div class="media">
                    <div class="media-icon mr-3">
                        <i class="{{ $icon }} text-{{ $color }}"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="dropdown-item-title mb-1 {{ $notification->read_at ? '' : 'font-weight-bold' }}">
                            {{ $data['title'] ?? 'Notification' }}
                        </h6>
                        <p class="text-sm mb-0">{{ $data['message'] ?? '' }}</p>
                        <small class="text-muted">{{ $time }}</small>
                    </div>
                </div>
            </a>
            <div class="dropdown-divider"></div>
        @empty
            <div class="dropdown-item text-center text-muted py-3">
                <i class="far fa-bell-slash fa-2x mb-2"></i>
                <p class="mb-0">No notifications</p>
            </div>
        @endforelse

        <div class="dropdown-divider"></div>
        <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">
            <i class="fas fa-eye mr-1"></i> View All Notifications
        </a>
    </div>
</li>
