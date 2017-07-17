@component('mail::message')
# Your credit balance won't cover this month

Your usage costs this month have exceeded the credit balance on your account.

Please login to your account and Top-up

@component('mail::button', ['url' => 'http://receive-sms.com/inbox'])
    Login
@endcomponent

Got questions about your bill? <a href="http://receive-sms.com/support">Contact Receive-SMS Support</a>
@endcomponent
