@component('mail::message')
Hello {{$name}},

Thank you for contacting us. {{$message}}


Best Regards,
{{ config('app.name') }} Support
@endcomponent
