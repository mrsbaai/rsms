@component('mail::message')
# {{$header}} Use coupon code:

<div class="coupon">
    {{$coupon}}
</div>


@component('mail::button', ['url' => 'http://receive-sms.com/login'])
Login to your account
@endcomponent

Hurry, offer ends <span class="enddate">{{$date}}</span>

Regards,<br>
{{ config('app.name') }}
@endcomponent