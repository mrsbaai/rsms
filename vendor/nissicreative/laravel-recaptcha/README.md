Laravel ReCaptcha
=================

> Easily integrate Google's reCAPTCHA into your Laravel 5+ project.


Installation
------------

```bash
composer require nissicreative/laravel-recaptcha
```

### Setup
Add the service provider to the providers array in app/config/app.php.  
**(You can skip this step if using Laravel 5.5+, as it will use Laravel's autodiscovery.)**

```php
Nissi\ReCaptcha\ReCaptchaServiceProvider::class,
```

### Configuration
> Visit [https://google.com/recaptcha](https://google.com/recaptcha) and register your site. Be sure to list all domains on which you will use the widget, including any local or staging domains (e.g. `localhost` or `mysite.test`). You will be assigned a Site Key and a Secret Key.

Add these reCAPTCHA keys to your .env file:

```env
RECAPTCHA_KEY=my-site-key
RECAPTCHA_SECRET=my-secret-key
```

This is the default config file:

```php
<?php

return [
    'key'           => env('RECAPTCHA_KEY'),
    'secret'        => env('RECAPTCHA_SECRET'),
    'script_url'    => 'https://www.google.com/recaptcha/api.js',
    'verify_url'    => 'https://www.google.com/recaptcha/api/siteverify',
    'error_message' => 'reCAPTCHA validation failed. Please try again.',
    'log_responses' => false,
];
```

If you would like to modify the config file and/or views, you may run:

```bash
php artisan vendor:publish
```

### Usage: The Form Page

In your page's `<head>` section, add this line to include the remote Google JavaScript file:

```blade
@include('recaptcha::script')
```

Then inside your form, insert the widget:

```blade
@include('recaptcha::widget')
```

That's it! You should now see the reCAPTCHA widget when you refresh the page.

### Validating the Request

Validation is simple: Just add a `recaptcha` rule to your validator. For example, in a controller:

```php
$this->validate($request, [
    'g-recaptcha-response' => 'required|recaptcha',
    // Other rules...
], [
    // Custom messages
    'g-recaptcha-response.required' => 'Please complete the reCAPTCHA.',
]);
```

The `recaptcha` rule takes care of sending the input to Google's servers via Guzzle, and returns `true` upon successful validation.

### Voilá! 
Enjoy your reduced-spam lifestyle.
