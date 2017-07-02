@component('mail::message')

Hello {{$name}},

The following
@if  (count($numbers) > 1)
numbers
@else
number
@endif
 has been successfully added to your account:

@component('mail::table')
    |   Number  |   Country     |   Reach   |   Expiration  |
    |   :-----  |   :------     |   :----   |   :---------  |
    @foreach ($numbers as $number)
    |   {{$number[0]}}  |   {{$number[1]}}     |   {{$number[2]}}   |   {{$number[3]}}  |
    @endforeach
@endcomponent

You can login to your account and start using you new
@if  (count($numbers) > 1)
numbers
@else
number
@endif
 now! 🙂

@component('mail::button', ['url' => config('app.url') . '/numbers'])
View Account
@endcomponent

Regards,<br>
{{ config('app.name') }} Team
@endcomponent
