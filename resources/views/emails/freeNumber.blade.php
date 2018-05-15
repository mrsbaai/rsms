@component('mail::message')

Hello {{$name}},

We noticed that you didn't add any numbers to your account yet.

We've dedicated a number to your account to test around. Here is your number:

<div class="coupon">
    {{$number}}
</div>

@component('mail::button', ['url' => config('app.url') . '/login'])
    View Account
@endcomponent

You have any questions? <a href="http://receive-sms.com/support">We're here to help</a>.

Have Fun!<br>
Receive-SMS Team
<br/>
<br/>

<div style="text-align: center;">
    <a style="font-size: 90%;" href="https://receive-sms.com/unsubscribe/{{$email}}">Unsubscribe</a>
</div>
@endcomponent
