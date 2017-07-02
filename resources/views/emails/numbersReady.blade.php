@component('mail::message')

Hello {{$name}},



The following number@if  (count($numbers) > 1)s@endif has been successfully added to your account:

@component('mail::table')
    |   Number  |   Country     |   Reach   |   Expiration  |
    |   :-----  |   :------     |   :----   |   :---------  |
    @foreach ($numbers as $number)
    |   {{$number[0]}}  |   {{$number[1]}}     |   {{$number[2]}}   |   {{$number[3]}}  |
    @endforeach
@endcomponent

You can login to your account and start using you new number@if  (count($numbers) > 1)s@endif now 🙂

@component('mail::button', ['url' => config('app.url') . '/numbers'])
View Account
@endcomponent

Regards,<br>
{{ config('app.name') }} Team
@endcomponent
