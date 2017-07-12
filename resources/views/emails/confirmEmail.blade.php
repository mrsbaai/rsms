@component('mail::message')
# Verify Your Email Address

Thanks for creating an account with {{ config('app.name') }}.
To confirm your email address, please follow the link:
{{ URL::to('register/verify/' . $confirmation_code) }}.

If you have not recently registered a {{ config('app.name') }} account, please ignore or delete this message.
This message was sent automatically. Please, do not reply.
@endcomponent
