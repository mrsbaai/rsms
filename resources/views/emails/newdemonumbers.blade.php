@component('mail::message')
# Hello {{$name}}!

We've just updated the demo numbers. Visit our home page and be the first to use them.

@component('mail::button', ['url' => 'http://receive-sms.com'])
Receive-SMS.com
@endcomponent

Their is also new fresh private numbers on our system. Here is a link if you need to register a private inbox:

@component('mail::button', ['url' => 'http://receive-sms.com/register'])
Register
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent