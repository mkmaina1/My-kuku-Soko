<x-mail::message>
# Message from My Kuku Soko Administration

Hello {{ $user->name }},

{!! nl2br(e($content)) !!}

<x-mail::button :url="route('dashboard')">
Visit My Kuku Soko
</x-mail::button>

<x-mail::panel>
**Important:** This is an official communication from My Kuku Soko administration.
Please do not reply to this email.
</x-mail::panel>

For any queries or concerns, please contact our support team:
- Email: support@mykukusoko.com
- Phone: +254 700 000 000
- Hours: Mon-Fri, 8:00 AM - 5:00 PM EAT

Best regards,<br>
**The My Kuku Soko Administration Team**
</x-mail::message>
