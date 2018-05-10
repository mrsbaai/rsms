@component('mail::message')

Hello {{$name}},

Some of your numbers need to be renewed.

The numbers will permanently removed from our database at {{$date}}, and cannot be recovered.

To avoid service interruption, please login and Top-Up your account:

@component('mail::button', ['url' => 'http://receive-sms.com/inbox'])
Login
@endcomponent

Thank you for using {{ config('app.name') }}! Got questions? <a href="http://receive-sms.com/support">We're here to help</a>.

@endcomponent
