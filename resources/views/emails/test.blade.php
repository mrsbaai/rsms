@component('mail::message')

Hello {{$name}},

This is a test email for automatic mailing system.

@component('mail::button', ['url' => config('app.url') . '/login'])
login
@endcomponent

Regards,<br>
{{ config('app.name') }} Team
@endcomponent
