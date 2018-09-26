<?php

namespace Nissi\ReCaptcha;

use Illuminate\Support\ServiceProvider;

class ReCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootConfig();
        $this->bootViews();

        app('validator')->extend('recaptcha', 'Nissi\\ReCaptcha\\ReCaptcha@validate');

        app('validator')->replacer('recaptcha', function ($attribute, $value, $parameters, $validator) {
            return config('recaptcha.error_message');
        });
    }

    /**
     * Load the config file, and enable publishing.
     *
     * @return  void
     */
    public function bootConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/recaptcha.php', 'recaptcha'
        );

        $this->publishes([
            __DIR__.'/config/recaptcha.php' => config_path('recaptcha.php'),
        ], 'recaptcha-config');
    }

    /**
     * Load the Google JavaScript and reCAPTCHA widget views, and enable publishing.
     *
     * @return  void
     */
    public function bootViews()
    {
        $this->loadViewsFrom(
            __DIR__.'/views', 'recaptcha'
        );

        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/recaptcha'),
        ], 'recaptcha-views');
    }
}
