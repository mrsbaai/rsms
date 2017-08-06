@component('mail::message')

    Hello {{$name}},<br/>

        Thank you for contacting us. {{$message}}

    Best Regards,<br/>
    {{ config('app.name') }} Support
@endcomponent
