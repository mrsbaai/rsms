@component('mail::message')

Hello {{$name}},

This is a test email for automatic mailing system.

@component('mail::button', ['url' => config('app.url') . '/login'])
login
@endcomponent

Regards,<br>
{{ config('app.name') }} Team
@endcomponent

<div style="text-align: center;">
    <a style="font-size: 90%;" href="https://receive-sms.com/unsubscribe/{{$email}}">Unsubscribe</a>
</div>