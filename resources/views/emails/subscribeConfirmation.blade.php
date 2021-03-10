@component('mail::message')
Hi there,

Thanks for subscribing to the {{ config('app.name') }} Newsletter!

To confirm your email address, please follow the link:
{{ URL::to('subscribe/verify/' . $email) }}.

If you received this email by mistake, simply delete it. You won't be subscribed if you don't click the confirmation link above.

This message was sent automatically. Please, do not reply.

For questions about this list: <a href="http://receive-sms.com/contact">Receive-SMS Contact</a>
@endcomponent

