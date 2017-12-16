
@extends('layouts.app')
@include('numbers_table')
@include('messages_table')


@section('share_buttons')
    <link rel="canonical" href="https://receive-sms.com" />

    <script src="https://apis.google.com/js/platform.js" async defer>
    </script>
        <table class='table share-table'>
            <tr>

                <td class='text-left'>
                    <iframe
                            src="https://platform.twitter.com/widgets/tweet_button.html?size=m&url=https%3A%2F%2Freceive-sms.com&hashtags=privacy"
                            width="70"
                            height="24"
                            title="Receive SMS Online"
                            style="border: 0; overflow: hidden;">
                    </iframe>
                </td>
                <td class='text-center'>
                    <g:plusone size="medium"></g:plusone>
                </td>
                <td class='text-right'>
                    <iframe
                            src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Freceive-sms.com&width=144&layout=button&action=recommend&size=small&show_faces=true&share=true&height=65&appId=464170053669778"
                            width="144"
                            height="24"
                            style="border:none;overflow:hidden"
                            scrolling="no"
                            frameborder="0"
                            allowTransparency="true">
                    </iframe>
                </td class='text-right'>
            </tr>
        </table>


@stop

@section('head')


    <!-- Open Graph data -->
    <meta property="og:title" content="Receive SMS Online" />
    <meta property="og:type" content="WebSite" />
    <meta property="og:url" content="https://{{config::get('settings.domain')}}" />
    <meta property="og:image" content="{{config::get('settings.thumbnail')}}" />
    <meta property="og:description" content="Receive SMS Online. Use our online numbers with your online registrations. Avoid any unwanted messages on your personal cellphone." />
    <meta property="og:site_name" content="Receive-SMS" />

    <meta name="keywords" content="receive, sms, online">
    <meta name="description" content="Receive SMS Online. Use our online numbers with your online registrations. Avoid any unwanted messages on your personal cellphone.">
    <title>Receive SMS Online</title>

@stop

@section('content')


        <div class="container width-fix col-sm-12">
            <div class="jumbotron welcome-texture">
                <h1>Receive SMS Online</h1>
                <p class="left_15">
                    This website can be useful if you want to protect your privacy by keeping your real phone number to yourself.
                </p>

                <h2>How To Use?</h2>
                <p class="left_15">
                    Here you will find some numbers, just use one with your online registrations, and the inbound messages will show up on this site within seconds.
                </p>

            </div>
        </div>

        <div class="container width-fix col-sm-12">
            <div class="jumbotron welcome-texture">
                <h2>Demo Numbers</h2>

                <p class="left_15">
                    Use the demo numbers below to receive text messages, click to check inbound SMS for a specific number.
                </p>
                @yield('numbers_table')


                <p class="left_15">
                    Subscribe to our news letter to get notified when ever we update the free numbers. Privacy is what this website is about. You will never receive spam from us.
                </p>
                <br/>

                {{ Form::open(array('action' => 'SubscribersController@subscribe', 'id' => 'subscribe-form'))}}
                    <div class="container-fluid">
                        <div class="col-lg-2 col-md-1"> </div>
                        <div class="col-lg-8 col-md-10">
                            <div class="input-group text-center">
                                <input
                                        type="email"
                                        class="form-control input-lg"
                                        data-toggle="tooltip"
                                        name="email"
                                        placeholder="[E-MAIL]"
                                        title="Enter your email here"
                                        onfocus="this.select();"
                                        onmouseup="return false;"
                                        required

                                >

                        <span class="input-group-btn">
                            <input type="submit" class="btn btn-primary btn-lg" value="Subscribe">
                        </span>

                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
                <br/>





            </div>
        </div>


        <div class="container width-fix col-sm-12">
            <div class="jumbotron welcome-texture">
                <h2>Inbound Messages - <span class="text-success">Live</span></h2>
                @yield('messages_table')

            </div>
        </div>





@stop



@section('bottom')

@stop