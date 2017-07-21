@component('mail::message')
    #{{$header}}Use coupon code #{{$coupon}}

@component('mail::button', ['url' => 'http://receive-sms.com/login'])Login to your account@endcomponent

    Hurry, offer ends {{$date}}!
    Regards,<br>
    {{ config('app.name') }}
@endcomponent