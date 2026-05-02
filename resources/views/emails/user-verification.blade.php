<x-mail::message>
# @if($status === 'approved')
✅ Account Verification Approved
@else
⚠️ Account Verification Update
@endif

<x-mail::panel style="{{ $status === 'approved' ? 'background-color: #d1fae5; border-left: 4px solid #10b981;' : 'background-color: #fee2e2; border-left: 4px solid #ef4444;' }}">
Dear {{ $user->name }},

@if($status === 'approved')
**Great news!** Your account verification has been **approved**.
@else
Your account verification request has been **reviewed**.
@endif
</x-mail::panel>

@if($status === 'approved')
<div style="padding: 20px; background-color: #f8fafc; border-radius: 8px; margin: 20px 0;">
    <h3 style="color: #059669; margin-top: 0;">What's Next?</h3>
    <ul style="color: #4b5563;">
        <li>Your profile now displays a verified badge</li>
        <li>Increased trust from potential customers</li>
        <li>Access to premium marketplace features</li>
        <li>Priority support for your queries</li>
    </ul>
</div>
@else
<div style="padding: 20px; background-color: #fef3c7; border-radius: 8px; margin: 20px 0;">
    <h3 style="color: #d97706; margin-top: 0;">Next Steps</h3>
    <p style="color: #92400e;">
        @if($notes)
        **Reason:** {{ $notes }}
        @endif
    </p>
    <p style="color: #4b5563;">
        You can update your information and reapply for verification through your dashboard.
    </p>
</div>
@endif

@if($notes && $status === 'approved')
<x-mail::panel>
**Admin Notes:**<br>
{{ $notes }}
</x-mail::panel>
@endif

<div style="margin: 30px 0; text-align: center;">
    <x-mail::button :url="route('dashboard')" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: inline-block;">
        @if($status === 'approved')
        Explore Verified Features
        @else
        Update Profile Information
        @endif
    </x-mail::button>
</div>

<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">

<div style="font-size: 14px; color: #6b7280; text-align: center;">
    <p>
        Need assistance? Contact our support team:<br>
        📧 support@mykukusoko.com | 📞 +254 700 000 000
    </p>
    <p>
        © {{ date('Y') }} My Kuku Soko. All rights reserved.<br>
        Nairobi, Kenya
    </p>
</div>
</x-mail::message>
