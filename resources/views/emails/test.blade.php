@component('mail::message')
    Hello {{$name}},

    Some numbers will be removed permanently from your account within 72 hours. You wont be able to receive any SMS messages from your registered online services.

    Please Login to your account, top up if necessary and renew your numbers:

    @component('mail::button', ['url' => config('app.url') . '/login'])
        Login
    @endcomponent

    Regards,<br>
    {{ config('app.name') }} Team
@endcomponent
