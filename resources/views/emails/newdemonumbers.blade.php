@component('mail::message')
Hello!

I just updated the demo numbers. Please visit Receive-SMS.com to be the first to use them.

@component('mail::button', ['url' => 'http://receive-sms.com'])
Receive-SMS.com
@endcomponent

@component('mail::table')
    |   Number  |   Country     |   Reach   |
    |   :-----  |   :------     |   :----   |
    @foreach ($numbers as $number)
    |   {{$number[0]}}  |   {{$number[1]}}     |   {{$number[2]}}   |
    @endforeach
@endcomponent

We have a new batch of fresh numbers on our system. So consider buying a private numbers 😉.

@component('mail::button', ['url' => 'http://receive-sms.com/register'])
Own a private number
@endcomponent

Cheers,<br>
Abe
{{ config('app.name') }} Admin

<div style="text-align: center;">
    <a style="font-size: 90%;" href="https://receive-sms.com/unsubscribe/{{$email}}">Unsubscribe</a>
</div>

@endcomponent

