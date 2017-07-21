@component('mail::message')
    # Thanks for topping up your account 🙂

    Here's your receipt.


    # Summary for {{$name}}


    @component('mail::button', ['url' => 'http://receive-sms.com/login'])
        View Account
    @endcomponent

    Got questions about your bill? <a href="http://receive-sms.com/support">Contact Receive-SMS Support</a>
@endcomponent