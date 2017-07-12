@component('mail::message')
# Verify Your Email Address

Hi there,

Welcome aboard and thanks for subscribing to the {{ config('app.name') }} mailing list!

We will let you know when ever we update our numbers. We will also email out special offers and coupons.

To confirm your email address, please follow the link:
{{ URL::to('subscribe/verify/' . $email) }}.

If you have not subscribed for {{ config('app.name') }} mailing list, please ignore or delete this message.
This message was sent automatically. Please, do not reply.
@endcomponent
