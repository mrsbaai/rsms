@component('mail::message')
# Hello {{$name}}!

We've just updated the demo numbers. Please visit Receive-SMS.com to be the first to use them.

@component('mail::button', ['url' => 'http://receive-sms.com'])
Receive-SMS.com
@endcomponent

We have a new batch of fresh numbers on our system. Consider getting a private number 😉.

@component('mail::button', ['url' => 'http://receive-sms.com/register'])
Get a private number
@endcomponent

Cheers,<br>
{{ config('app.name') }}
@endcomponent