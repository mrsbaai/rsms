@component('mail::message')
# {{$header}}

Use coupon code:

<div class="coupon">
    {{$coupon}}
</div>


@component('mail::button', ['url' => 'http://receive-sms.com/login'])
Redeem Now
@endcomponent

Hurry, offer ends <span class="enddate">{{$date}}</span><br>
{{ config('app.name') }} Team
<br/>
<br/>

<div style="text-align: center;">
    <a style="font-size: 90%;" href="https://receive-sms.com/unsubscribe/{{$email}}">Unsubscribe</a>
</div>
@endcomponent