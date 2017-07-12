@component('mail::message')
    Hi there,

Thanks for subscribing to the {{ config('app.name') }} newsletter!

We will let you know when ever we update our numbers. We will also email out special offers and coupons.

To confirm your email address, please follow the link:
{{ URL::to('subscribe/verify/' . $email) }}.

If you have not subscribed for {{ config('app.name') }} newsletter, please follow this link to unsubscribe.
    {{ URL::to('unsubscribe/' . $email) }}.
    This message was sent automatically. Please, do not reply.
@endcomponent
