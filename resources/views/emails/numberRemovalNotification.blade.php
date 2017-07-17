@component('mail::message')
    Hello {{$name}},

    The following
    @if  (count($numbers) > 1)
        numbers
    @else
        number
    @endif
    Will be removed permanently from your account within 72 hours. You wont be able to receive any SMS messages from your registered online services.

    @component('mail::table')
        |   Number  |   Country     |   Reach   |   Expiration  |
        |   :-----  |   :------     |   :----   |   :---------  |
        @foreach ($numbers as $number)
            |   {{$number[0]}}  |   {{$number[1]}}     |   {{$number[2]}}   |   {{$number[3]}}  |
        @endforeach
    @endcomponent

    Please Login to your account now and top up:

    @component('mail::button', ['url' => config('app.url') . '/inbox'])
        Login
    @endcomponent

    Regards,<br>
    {{ config('app.name') }} Team
@endcomponent
