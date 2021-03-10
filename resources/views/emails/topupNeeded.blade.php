@component('mail::message')

Hello {{$name}},

Some of your numbers will be permanently removed from our database at {{$date}}, and cannot be recovered.

To avoid this service interruption, please login and Top-Up your account:

@component('mail::button', ['url' => 'http://receive-sms.com/inbox'])
Login
@endcomponent

Use this code "Today15Off" to get a 15% discount.

Thank you for using {{ config('app.name') }}. Got questions? <a href="http://receive-sms.com/support">We're here to help</a>.

@endcomponent

<div style="text-align: center;">
    <a style="font-size: 90%;" href="https://receive-sms.com/unsubscribe/{{$email}}">Unsubscribe</a>
</div>
