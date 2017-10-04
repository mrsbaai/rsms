
@extends('layouts.app')
@include('numbers_table')
@include('messages_table')


@section('head')

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
                                        placeholder="nickname@example.com"
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
