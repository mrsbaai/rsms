@component('mail::message')

    Hello {{$name}},

    The following number(s) has been successfully added to your account:

@component('mail::table')
    |   Number  |   Country     |   Reach   |   Expiration  |
    |   :----:  |   :-----:     |   :---:   |   :--------:  |
    @foreach ($numbers as $number)
    |   {{$number[0]}}  |   {{$number[1]}}     |   {{$number[2]}}   |   {{$number[3]}}  |
    @endforeach
@endcomponent

    Login to your account to see the changes.

@component('mail::button', ['url' => config('app.url') . '/numbers'])
View Account
@endcomponent

Regards,<br>
{{ config('app.name') }} Team
@endcomponent
