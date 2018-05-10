@component('mail::message')

Hello {{$name}},

Some of your numbers will be removed permanently from your account at {{$date}}.

The numbers removed will be permanently removed from our database, and cannot be recovred.

To avoid service interruption, please login and Top-Up your account:

@component('mail::button', ['url' => 'http://receive-sms.com/inbox'])
Login
@endcomponent

Thank you for using {{ config('app.name') }}! Got questions? <a href="http://receive-sms.com/support">We're here to help</a>.

@endcomponent
